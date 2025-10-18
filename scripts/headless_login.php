<?php
// Usage: php headless_login.php [host]
$host = $argv[1] ?? 'http://localhost';
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

$client = new Client(['base_uri' => rtrim($host, '/'), 'http_errors' => false]);
$jar = new CookieJar();

echo "Target host: $host\n";

// 1) GET /login to fetch CSRF token and cookies
$res = $client->request('GET', '/login', ['cookies' => $jar]);
$body = (string) $res->getBody();

if ($res->getStatusCode() >= 400) {
    echo "GET /login returned status: " . $res->getStatusCode() . "\n";
}

// Try to extract hidden _token value
if (preg_match('/name="_token" value="([^"]+)"/i', $body, $m)) {
    $csrf = $m[1];
    echo "CSRF token found: " . substr($csrf, 0, 12) . "...\n";
} else {
    // Try meta tag (rare)
    if (preg_match('/meta name="csrf-token" content="([^"]+)"/i', $body, $m2)) {
        $csrf = $m2[1];
        echo "CSRF token found in meta: " . substr($csrf, 0, 12) . "...\n";
    } else {
        $csrf = null;
        echo "No CSRF token found in login page.\n";
    }
}

// Show cookies received from GET
$cookies = [];
foreach ($jar->toArray() as $c) {
    $cookies[$c['Name']] = $c;
}
if (!empty($cookies)) {
    echo "Cookies from GET /login:\n";
    foreach ($cookies as $name => $c) {
        echo " - $name = " . ($c['Value'] ?? '') . "; Domain=" . ($c['Domain'] ?? '') . "; Secure=" . ($c['Secure'] ? 'yes' : 'no') . "\n";
    }
} else {
    echo "No cookies received from GET /login.\n";
}

// 2) POST /login with credentials
$email = 'admin@gmail.com';
$password = '123456';
$post = [
    'form_params' => array_filter([ '_token' => $csrf, 'email' => $email, 'password' => $password ]),
    'cookies' => $jar,
    'allow_redirects' => false,
];

$postRes = $client->request('POST', '/login', $post);

echo "POST /login status: " . $postRes->getStatusCode() . "\n";

// Print Set-Cookie headers
$setCookies = $postRes->getHeader('Set-Cookie');
if (!empty($setCookies)) {
    echo "Set-Cookie from POST:\n";
    foreach ($setCookies as $sc) echo " - $sc\n";
} else {
    echo "No Set-Cookie headers in POST response.\n";
}

// Print Location header if redirect
$loc = $postRes->getHeaderLine('Location');
if ($loc) echo "Location: $loc\n";

// Show cookies after POST
$cookiesAfter = [];
foreach ($jar->toArray() as $c) {
    $cookiesAfter[$c['Name']] = $c;
}
if (!empty($cookiesAfter)) {
    echo "Cookies after POST:\n";
    foreach ($cookiesAfter as $name => $c) {
        echo " - $name = " . ($c['Value'] ?? '') . "; Domain=" . ($c['Domain'] ?? '') . "; Secure=" . ($c['Secure'] ? 'yes' : 'no') . "\n";
    }
} else {
    echo "No cookies stored after POST.\n";
}

// 3) Check sessions table in Laravel app to see last sessions
// We'll bootstrap the app and query DB
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('sessions')) {
    echo "sessions table missing.\n";
    exit(0);
}

$rows = DB::table('sessions')->orderByDesc('last_activity')->limit(10)->get();
$count = DB::table('sessions')->count();

echo "sessions count: $count\n";
foreach ($rows as $r) {
    echo "session id=" . ($r->id ?? '') . " last_activity=" . date('Y-m-d H:i:s', $r->last_activity ?? 0) . " payload_len=" . strlen($r->payload ?? '') . "\n";
}

// Try to match any session id with cookie (support both names)
$cookieNames = ['laravel-session', 'laravel_session', env('SESSION_COOKIE')];
$cookieNames = array_filter($cookieNames);
$found = false;
foreach ($cookieNames as $name) {
    if (isset($cookiesAfter[$name])) {
        $cookieVal = $cookiesAfter[$name]['Value'];
        echo "$name cookie (raw): " . substr($cookieVal,0,30) . "...\n";

        // Try to decrypt if possible (Laravel encrypts cookie values)
        try {
            $appEncrypter = $app->make('encrypter');
            $decodedCookie = urldecode($cookieVal);
            $decrypted = $appEncrypter->decrypt($decodedCookie);
            echo "Decrypted cookie: " . substr($decrypted,0,60) . "...\n";
            $cookieValToMatch = $decrypted;
        } catch (Exception $e) {
            echo "Cookie decrypt error: " . $e->getMessage() . "\n";
            $cookieValToMatch = urldecode($cookieVal);
        }

        // For database driver, cookie value may be session id
        $matching = DB::table('sessions')->where('id', $cookieValToMatch)->first();
        if ($matching) {
            echo "Found matching session row for cookie id ($name).\n";
            $found = true;
            break;
        } else {
            echo "No matching session row for cookie id ($name).\n";
        }
    }
}
if (!$found) echo "No laravel session cookie matched any DB session id.\n";

// 4) Try to GET an admin page using the cookie jar to ensure the session is recognized
echo "\nAttempting GET /admin/orders with stored cookies...\n";
$adminRes = $client->request('GET', '/admin/orders', ['cookies' => $jar, 'http_errors' => false]);
echo "GET /admin/orders status: " . $adminRes->getStatusCode() . "\n";
$adminBody = (string) $adminRes->getBody();
echo "--- Response snippet (first 800 chars) ---\n";
echo substr(trim(preg_replace('/\s+/', ' ', strip_tags($adminBody))), 0, 800) . "\n";
echo "--- End snippet ---\n";

// 4) Try to GET the admin dashboard using the same cookie jar to verify session recognized
echo "\nAttempting GET /admin/dashboard with stored cookies...\n";
$dashRes = $client->request('GET', '/admin/dashboard', ['cookies' => $jar, 'http_errors' => false]);
echo "GET /admin/dashboard status: " . $dashRes->getStatusCode() . "\n";
$dashBody = (string) $dashRes->getBody();
if ($dashRes->getStatusCode() === 200) {
    // Print snippet around the Dashboard title
    $pos = strpos($dashBody, 'Tổng quan');
    if ($pos !== false) {
        echo "Found 'Tổng quan' in dashboard HTML.\n";
    } else {
        echo "'Tổng quan' not found in dashboard HTML; printing first 400 chars:\n";
        echo substr($dashBody, 0, 400) . "\n";
    }
} else {
    echo "Dashboard GET returned non-200. Response length: " . strlen($dashBody) . " chars\n";
}

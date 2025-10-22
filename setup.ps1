Param(
    [switch]$Force
)

Function Exec-Command {
    param($cmd)
    Write-Host ">> $cmd" -ForegroundColor Cyan
    $proc = Start-Process -FilePath pwsh -ArgumentList "-NoProfile","-Command","$cmd" -NoNewWindow -Wait -PassThru -ErrorAction SilentlyContinue
    if ($proc.ExitCode -ne 0) {
        throw "Command failed: $cmd (exit $($proc.ExitCode))"
    }
}

Write-Host "Running project setup script..." -ForegroundColor Green

if (-not (Test-Path .env)) {
    Write-Host "Creating .env from .env.example" -ForegroundColor Yellow
    Copy-Item .env.example .env -Force
} else {
    if (-not $Force) {
        Write-Host ".env already exists. Use -Force to overwrite." -ForegroundColor Yellow
    }
}

if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "composer not found in PATH. Please install Composer first." -ForegroundColor Red
    exit 1
}

Write-Host "Installing PHP dependencies (composer)..." -ForegroundColor Green
composer install --no-interaction

if (Test-Path package.json) {
    if (-not (Get-Command npm -ErrorAction SilentlyContinue)) {
        Write-Host "npm not found in PATH. Skipping JS dependencies." -ForegroundColor Yellow
    } else {
        Write-Host "Installing JS dependencies (npm)..." -ForegroundColor Green
        npm ci
        Write-Host "Building assets (dev)..." -ForegroundColor Green
        npm run dev
    }
}

Write-Host "Generating APP_KEY if not set..." -ForegroundColor Green
php artisan key:generate

Write-Host "Running migrations (with seed)..." -ForegroundColor Green
php artisan migrate --seed

Write-Host "Setup complete. Run 'php artisan serve' to start the dev server." -ForegroundColor Green

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share menu config to all views (safe fallback)
        $menuItems = config('menu') ?? [];
        View::share('menuItems', $menuItems);

        // Compute cart item count robustly: prefer Darryldecode Cart if available,
        // otherwise fall back to session-stored cart array count.
        $cartItemCount = 0;
        try {
            // If the Cart facade is available at runtime, use it.
            $cartItemCount = Cart::getTotalQuantity() ?? 0;
        } catch (\Throwable $e) {
            // ignore and fallback to session
            $sessionCart = Session::get('cart');
            $cartItemCount = is_array($sessionCart) ? count($sessionCart) : 0;
        }

        // Share with all views
        View::share('cartItemCount', $cartItemCount);
    }
}
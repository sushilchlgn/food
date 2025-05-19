<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // <-- Make sure this is present

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { // <-- Your existing closure
        // Register your route middleware aliases here
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'restaurant_owner' => \App\Http\Middleware\RestaurantOwnerMiddleware::class,
            'checkout.redirect' => \App\Http\Middleware\CheckoutRedirectMiddleware::class,
            // 'customer' => \App\Http\Middleware\CustomerMiddleware::class, // If you have it
            // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Breeze might add this or similar
        ]);

        // You can also add global middleware, group middleware, etc.
        // Example for global middleware (already handled by Laravel default):
        // $middleware->web(append: [
        //     \App\Http\Middleware\EncryptCookies::class,
        // ]);

        // Example for middleware groups (already handled by Laravel default):
        // $middleware->group('web', [
        //     \App\Http\Middleware\EncryptCookies::class,
        //     // ... other web middleware
        // ]);
        // $middleware->group('api', [
        //     // ... api middleware
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
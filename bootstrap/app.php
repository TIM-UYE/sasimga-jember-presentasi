<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetLocale;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__ . '/../routes/web.php', commands: __DIR__ . '/../routes/console.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware): void {
        // Web middleware tambahan
        $middleware->web(append: [SetLocale::class]);

        // Alias middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // CSRF token validation exceptions
        // Midtrans webhook routes MUST be excluded from CSRF
        $middleware->validateCsrfTokens(except: ['midtrans/webhook', 'midtrans/finish', 'midtrans/unfinish', 'midtrans/error', 'midtrans/test', 'payment/midtrans/*']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();

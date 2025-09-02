<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Localization;
use App\Http\Middleware\SuperAdminOnly;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\WriterOnly;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            Localization::class,
        ]);

        $middleware->alias([
            'super_admin' => SuperAdminOnly::class,
            'admin_only'  => AdminOnly::class,
            'writer_only' => WriterOnly::class,
           
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

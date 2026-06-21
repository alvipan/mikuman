<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(fn () => route('routers.index'));
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'installed' => \App\Http\Middleware\EnsureAppInstalled::class,
            'not_installed' => \App\Http\Middleware\EnsureAppNotInstalled::class,
            'connected' => \App\Http\Middleware\EnsureRouterConnected::class,
            'disconnected' => \App\Http\Middleware\EnsureRouterDisconnected::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\Connected;
use App\Http\Middleware\Disconnected;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('connected', [
            Connected::class,
        ]);
     
        $middleware->prependToGroup('connected', [
            Connected::class,
        ]);

        $middleware->appendToGroup('disconnected', [
            Disconnected::class,
        ]);
     
        $middleware->prependToGroup('disconnected', [
            Disconnected::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

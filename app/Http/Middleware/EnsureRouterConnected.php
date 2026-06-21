<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRouterConnected
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('active_router_id')) {
            return redirect()->route('routers.index');
        }

        return $next($request);
    }
}
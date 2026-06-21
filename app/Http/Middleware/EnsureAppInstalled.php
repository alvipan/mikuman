<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class EnsureAppInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (! User::exists()) {
            return redirect()->route('setup');
        }

        return $next($request);
    }
}
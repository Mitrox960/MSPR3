<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class StoreLastRoute
{
    public function handle($request, Closure $next)
    {
        // Stockez l'URL précédente en session
        Session::put('previous_url', url()->previous());

        return $next($request);
    }
}

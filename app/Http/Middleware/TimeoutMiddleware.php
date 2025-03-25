<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TimeoutMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get current time
        $start = microtime(true);
        $response = $next($request);

        $duration = microtime(true) - $start; // Get request time taken

        if ($duration > 10) { // If request takes longer than 10 seconds
            Auth::logout();
            Session::invalidate();
            \Log::warning("⏳ Request Timeout: URL {$request->fullUrl()} took {$duration} seconds.");
            return redirect()->route('auth.admin')->with(SESSION_ERROR, 'Timeout');
        }

        return $response;
    }
}

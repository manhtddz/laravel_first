<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\Response;

class ClearTempFileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $files = Storage::disk('public')->files('temp');

        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }

        return $response;
    }
}

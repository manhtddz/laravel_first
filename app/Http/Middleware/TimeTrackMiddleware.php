<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Ramsey\Uuid\Type\Time;

class TimeTrackMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Lấy thời gian hiện tại
        $currentTime = Carbon::now();
        Session::put('last_activity', $currentTime->toDateTimeString());
        Session::save();

        \Log::info("✅ Cập nhật `last_activity`: " . Session::get('last_activity'));
        return $next($request);
    }
}

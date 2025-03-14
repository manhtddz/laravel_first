<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class TimeoutMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Lấy thời gian hiện tại
        $currentTime = Carbon::now();

        // Kiểm tra xem session có lưu last_activity không
        if (Session::has('last_activity')) {
            $lastActivity = Carbon::parse(Session::get('last_activity'));
            $inactiveTime = $lastActivity->diffInSeconds($currentTime);

            \Log::info("🕒 Thời gian không hoạt động: [{$inactiveTime}] giây");

            if ($inactiveTime > 5) { // Timeout sau 10 giây
                \Log::warning("⚠️ User bị logout do không hoạt động!");

                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();

                return redirect()->route('auth.admin')->with('error', 'Bạn đã bị logout do không hoạt động.');
            }
        }

        // Cập nhật session `last_activity`
       

        return $next($request);
    }
}

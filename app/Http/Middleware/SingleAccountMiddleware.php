<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Symfony\Component\HttpFoundation\Response;

class SingleAccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        \Log::info('Middleware đã chạy');
        \Log::info('User Session ID: ' . Session::get('user_session_id'));
        \Log::info('Current Session ID: ' . Session::getId());
        if (Auth::check()) {
            // Nếu session hiện tại không trùng với session khi đăng nhập -> logout
            if (Session::get('user_session_id') !== Session::getId()) {
               
                Auth::logout();
                Session::flush(); // Xóa session để tránh lưu thông tin cũ
                return redirect(route('auth.admin'))->with('error', 'Bạn đã đăng nhập ở tab khác.');
            }
        }

        return $next($request);
    }
}

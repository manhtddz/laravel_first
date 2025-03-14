<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Services\EmployeeService;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Session;
use Str;

class AuthController extends Controller
{
    private EmployeeService $employeeService;
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }
    public function index()
    {
        return view('auth.login');
    }

    public function login(AuthRequest $request)
    {
        $token = Str::random(40);

        $foundEmp = $this->employeeService->findByEmailIgnoreDelFlag($request->input('email'));
        if (empty($foundEmp)) {
            return redirect()->route('auth.admin')->with("emailError", "Email not found");
        }
        if($foundEmp->del_flag == 1) {
            return redirect()->route('auth.admin')->with("emailError", "Login failed");
        }
        $credentials = [
            "email" => $request->input('email'),
            "password" => $request->input('password')
        ];
        if (Auth::attempt($credentials)) {
            Session::put('user_session_id', $token);
            return redirect()->route('team.index')->with("success", "vaof dc r");
        }
        return redirect()->route('auth.admin')->with("emailError", "Login failed");
    }
    public function logout(Request $request)
    {
        // static
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->route("auth.admin");
    }
}

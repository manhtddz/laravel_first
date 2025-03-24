<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Services\EmployeeService;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

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
        $foundEmp = $this->employeeService->findActiveEmployeeByEmail($request->input('email'));
        if (empty($foundEmp)) {
            session()->put('login_email', $request->input('email'));
            $foundEmp = $this->employeeService->findNotActiveEmployeeByEmail($request->input('email'));
            if ($foundEmp) {
                return redirect()->route('auth.admin')->with(SESSION_EMAIL_ERROR, LOGIN_FAILED);
            }
            return redirect()->route('auth.admin')->with(SESSION_EMAIL_ERROR, EMAIL_NOT_FOUND);
        }
        $credentials = [
            "email" => $request->input('email'),
            "password" => $request->input('password')
        ];
        if (Auth::attempt($credentials)) {
            return redirect()->route('team.index')->with(SESSION_SUCCESS, LOGIN_SUCCESS);
        }
        session()->put('login_email', $request->input('email'));
        return redirect()->route('auth.admin')->with(SESSION_EMAIL_ERROR, LOGIN_FAILED);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        return redirect()->route("auth.admin");
    }
}

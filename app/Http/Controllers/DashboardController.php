<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        $config = $this->config();
        return view('dashboard.layout', compact(['config']));
    }
    public function config()
    {
        return [
            'user' => Auth::user(),
            'template' => "dashboard.home.index",
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('backend.pages.dashboard');
    }

    public function logoutHandler(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('fail', 'You have been logged out');
    }

    public function profileView(Request $request)
    {
        $data=[
            // 'user'=>Auth::user(),
            'pageTitle'=>'Admin Profile'
        ];
        return view('backend.pages.profile', $data);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Mock credentials
    private $mockUsers = [
        ['email' => 'admin@addashboard.id', 'password' => 'admin123', 'name' => 'Admin Dashboard', 'role' => 'Admin'],
        ['email' => 'demo@addashboard.id',  'password' => 'demo123',  'name' => 'Demo User',       'role' => 'Viewer'],
    ];

    public function showLogin()
    {
        if (Session::get('auth_user')) {
            return redirect()->route('dashboard.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:4',
        ]);

        foreach ($this->mockUsers as $user) {
            if ($user['email'] === $request->email && $user['password'] === $request->password) {
                Session::put('auth_user', $user);
                return redirect()->route('dashboard.index');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function logout()
    {
        Session::forget('auth_user');
        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Simulasikan pengiriman email reset
        return back()->with('status', 'Kami telah mengirimkan tautan reset password ke email Anda!');
    }
}

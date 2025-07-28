<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            switch ($user->role->name) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'kepala_sekolah':
                    return redirect()->intended('/kepala_sekolah/dashboard');
                case 'guru':
                    return redirect()->intended('/guru/dashboard');
                case 'murid':
                    return redirect()->intended('/murid/dashboard');
                case 'staff_tu':
                    return redirect()->intended('/staff_tu/dashboard');
                default:
                    return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Ganti dengan halaman setelah logout berhasil
    }
}

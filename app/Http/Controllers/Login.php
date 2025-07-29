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
            $roleName = str_replace('_', ' ', $user->role->name); // Format nama role
            $welcomeMessage = "Selamat datang {$user->name}! Anda berhasil login sebagai {$roleName}.";

            switch ($user->role->name) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard')
                        ->with('success', $welcomeMessage);
                case 'kepala_sekolah':
                    return redirect()->intended('/kepala_sekolah/dashboard')
                        ->with('success', $welcomeMessage);
                case 'guru':
                    return redirect()->intended('/guru/dashboard')
                        ->with('success', $welcomeMessage);
                case 'murid':
                    return redirect()->intended('/murid/dashboard')
                        ->with('success', $welcomeMessage);
                case 'staff_tu':
                    return redirect()->intended('/staff_tu/dashboard')
                        ->with('success', $welcomeMessage);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email')
            ->with('error', 'Login gagal. Email atau password salah.');
    }

    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'Pengguna';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', "{$userName}, Anda berhasil logout. Sampai jumpa kembali!");
    }
}

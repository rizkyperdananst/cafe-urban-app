<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials) && Auth::user()->is_active == 1) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'Super Admin' || Auth::user()->role == 'Admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Berhasil login!');
            } elseif (Auth::user()->role == 'Customer') {
                return redirect()->intended('/customer/dashboard')->with('success', 'Berhasil login!');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email');
    }

    // tampilkan form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // proses register
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name ?? null,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/login')->with('success', 'Akun berhasil dibuat!');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout');
    }
}

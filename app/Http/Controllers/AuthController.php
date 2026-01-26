<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user has a role, purely for UX redirection logic (optional)
            // But Auth::attempt already verified credentials.
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '.');
        }

        return back()->withErrors([
            'email' => 'Informasi akun tidak ditemukan atau kata sandi salah.',
        ])->with('error', 'Gagal masuk. Periksa kembali email dan kata sandi Anda.')->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:4'],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        // Automatically assign 'student' role
        $studentRole = \App\Models\Role::where('slug', 'student')->first();
        if ($studentRole) {
            $user->roles()->attach($studentRole);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('placement.index')->with('success', 'Akun berhasil dibuat! Silakan mulai tes penempatan.');
    }
}

@extends('layouts.app')

@section('title', 'Masuk (Login)')

@section('content')
<div class="fixed inset-0 z-50 bg-white flex w-full h-full">
    <!-- Left Panel (Manifesto) -->
    <div class="w-1/2 h-full bg-black border-r border-border relative hidden lg:flex flex-col justify-between p-12 overflow-hidden">
        <!-- Background Image -->
            <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1619410283995-43d9134e7656?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                 class="w-full h-full object-cover brightness-10 opacity-60 grayscale mix-blend-multiply" 
                 alt="Tech Pattern">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/40"></div>
        </div>
        <div class="relative z-10">
            <div class="w-8 h-8 mb-4">
                <img src="{{ asset('storage/assets/favicon.ico') }}" class="h-25 w-auto object-contain brightness-0 invert" alt="Logo">
            </div> 
        </div>
        
        <div class="relative z-10">
            <h1 class="text-6xl font-bold tracking-tight leading-none text-accent">
                TECHALEARN
            </h1>
            <h2 class="text-6xl font-bold tracking-tight leading-none mb-6 text-white">
                Technology<br><span class="text-white/50">Learning.</span>
            </h2>
            <p class="text-gray-400 max-w-sm text-lg font-light leading-relaxed">
                Tempat belajar teknologi yang inovatif, modern, dan inklusif.
            </p>
        </div>

        <div class="relative z-10 text-xs text-gray-500 uppercase tracking-widest font-mono">
            System v0.0.1-beta • 2026
        </div>
    </div>

    <!-- Right Panel (Login Form) -->
    <div class="w-full lg:w-1/2 h-full flex items-center justify-center p-8 bg-white overflow-y-auto">
        <div class="w-full max-w-sm space-y-10">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Autentikasi</h2>
                <p class="text-text-muted text-sm">Akses Sistem Manajemen TechALearn.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false }">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Email Pengguna</label>
                    <x-ui.input 
                        class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors"
                        type="email" 
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email anda" 
                        required autofocus />
                    @error('email')
                        <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               placeholder="Masukkan kata sandi akun anda" 
                               required 
                               class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors"
                        >
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-text-muted hover:text-black">
                            <template x-if="!showPassword">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </template>
                            <template x-if="showPassword">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </template>
                        </button>
                    </div>
                    @error('password')
                         <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <x-ui.button type="submit" class="w-full">
                    Masuk ke Sistem
                </x-ui.button>
            </form>
            <div class="text-center text-sm text-text-muted">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-black hover:underline">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection

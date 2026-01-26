@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="fixed inset-0 z-50 bg-white flex w-full h-full">
    <!-- Left Panel (Manifesto) -->
    <div class="w-1/2 h-full bg-surface-off border-r border-border p-12 flex flex-col justify-between hidden lg:flex">
        <div>
            <div class="w-8 h-8 bg-black"></div> 
        </div>
        <div>
            <h1 class="text-6xl font-bold tracking-tight leading-none mb-6 text-text-main">
                TECHALEARN<br>Technology<br><span class="text-accent">Learning.</span>
            </h1>
            <p class="text-text-muted max-w-sm text-lg">
                Tempat belajar teknologi yang inovatif, modern, dan inklusif.
            </p>
        </div>
        <div class="text-xs text-text-muted uppercase tracking-widest">
            System v2.4 • 2026
        </div>
    </div>

    <!-- Right Panel (Register Form) -->
    <div class="w-full lg:w-1/2 h-full flex items-center justify-center p-8 bg-white overflow-y-auto">
        <div class="w-full max-w-sm space-y-10" x-data="{ showPassword: false, showPasswordConfirm: false }">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Buat Akun</h2>
                <p class="text-text-muted text-sm">Mulai perjalanan belajar Anda di TechALearn.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Name --}}
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-text-main">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors"
                        placeholder="Masukkan nama lengkap">
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-text-main">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors"
                        placeholder="nama@email.com">
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-text-main">Kata Sandi</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               id="password"
                               placeholder="Masukkan kata sandi" 
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
                </div>

                {{-- Confirm Password --}}
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-text-main">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input :type="showPasswordConfirm ? 'text' : 'password'" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               placeholder="Ulangi kata sandi" 
                               required 
                               class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors"
                        >
                        <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-text-muted hover:text-black">
                            <template x-if="!showPasswordConfirm">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </template>
                            <template x-if="showPasswordConfirm">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </template>
                        </button>
                    </div>
                </div>

                <x-ui.button type="submit" class="w-full">
                    Daftar Sekarang
                </x-ui.button>

                <div class="text-center text-sm text-text-muted">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-bold text-black hover:underline">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

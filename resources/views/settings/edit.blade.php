@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-text-main">Pengaturan Akun</h1>
            <p class="text-text-muted mt-1">Kelola informasi profil dan keamanan akun Anda.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-text-muted">
            <span>Akun</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-text-main font-medium">Pengaturan</span>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 bg-green-50 text-green-700 border border-green-100 flex items-center gap-3 animate-fade-in">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Profile Section -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Personal Info Card -->
                <div class="bg-white border border-border p-8 group hover:border-gray-300 transition-colors relative overflow-hidden">
                   <div class="absolute top-0 right-0 w-24 h-24 bg-surface-off rounded-bl-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
                   
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-8 border-b border-border pb-6">
                            <div class="w-12 h-12 bg-black text-white flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold tracking-tight">Profil Saya</h2>
                                <p class="text-sm text-text-muted">Informasi dasar akun Anda</p>
                            </div>
                        </div>

                        <div class="grid gap-6">
                            <div class="space-y-2">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-text-muted">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                    class="w-full px-4 py-3 bg-surface-off border-border focus:border-black focus:ring-0 transition-colors placeholder-gray-400"
                                    placeholder="Masukkan nama lengkap"
                                    required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-text-muted">Alamat Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                    class="w-full px-4 py-3 bg-surface-off border-border focus:border-black focus:ring-0 transition-colors placeholder-gray-400"
                                    placeholder="nama@email.com"
                                    required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Card -->
                <div class="bg-white border border-border p-8 group hover:border-gray-300 transition-colors relative overflow-hidden">
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-8 border-b border-border pb-6">
                            <div class="w-12 h-12 bg-white border border-black text-black flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold tracking-tight">Keamanan</h2>
                                <p class="text-sm text-text-muted">Perbarui kata sandi akun</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="p-4 bg-surface-off border border-border flex items-start gap-3">
                                <svg class="w-5 h-5 text-text-muted mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm text-text-muted leading-relaxed">
                                    Kosongkan kolom di bawah ini jika Anda tidak ingin mengubah password saat ini. Password baru minimal harus terdiri dari 8 karakter.
                                </p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="col-span-full md:col-span-1 space-y-2">
                                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-text-muted">Password Saat Ini</label>
                                    <input type="password" name="current_password" id="current_password" 
                                        class="w-full px-4 py-3 bg-surface-off border-border focus:border-black focus:ring-0 transition-colors placeholder-gray-400"
                                        placeholder="••••••••">
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6 pt-4 border-t border-dashed border-gray-200">
                                <div class="space-y-2">
                                    <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-text-muted">Password Baru</label>
                                    <input type="password" name="new_password" id="new_password" 
                                        class="w-full px-4 py-3 bg-surface-off border-border focus:border-black focus:ring-0 transition-colors placeholder-gray-400"
                                        placeholder="••••••••">
                                    @error('new_password')
                                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-text-muted">Konfirmasi Password</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                        class="w-full px-4 py-3 bg-surface-off border-border focus:border-black focus:ring-0 transition-colors placeholder-gray-400"
                                        placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar/Info Section (Right Column) -->
            <div class="space-y-6">
                <div class="bg-black text-white p-6 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-accent/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                    <div class="relative">
                        <h3 class="text-lg font-bold mb-2">Simpan Perubahan</h3>
                        <p class="text-gray-400 text-sm mb-6 leading-relaxed">
                            Pastikan semua data yang Anda masukkan sudah benar sebelum menyimpan perubahan.
                        </p>
                        <button type="submit" class="w-full bg-white text-black px-6 py-4 text-sm font-bold uppercase tracking-widest hover:bg-accent hover:text-white transition-all duration-300">
                            Simpan Data
                        </button>
                    </div>
                </div>

                <div class="bg-surface-off border border-border p-6">
                    <h3 class="font-bold text-sm uppercase tracking-wide mb-4 text-text-main">Info Akun</h3>
                    <ul class="space-y-4 text-sm">
                        <li class="flex justify-between items-center py-2 border-b border-border border-dashed">
                            <span class="text-text-muted">Role</span>
                            <span class="font-medium bg-black text-white px-2 py-1 text-xs uppercase tracking-wider">{{ $user->roles->first()->name ?? 'User' }}</span>
                        </li>
                        <li class="flex justify-between items-center py-2 border-b border-border border-dashed">
                            <span class="text-text-muted">Bergabung</span>
                            <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="flex justify-between items-center py-2 border-b border-border border-dashed">
                            <span class="text-text-muted">Status</span>
                            <span class="font-medium text-green-600 flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Aktif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dasbor Admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-12">
    <div>
        <h2 class="text-4xl font-semibold text-text-main mb-2">Panel Admin</h2>
        <p class="text-text-muted">Kontrol penuh atas sistem dan pengguna.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 border-t border-l border-border bg-white">
        <div class="p-8 border-r border-b border-border">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-4">Total Pengguna</p>
            <p class="text-5xl font-light">{{ $totalUsers }}</p>
        </div>
        <div class="p-8 border-r border-b border-border bg-surface-off">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-4">Manajemen</p>
            <div class="space-y-2">
                 <a href="#" class="block text-sm font-bold text-accent hover:underline">+ Tambah Pengguna</a>
                 <a href="#" class="block text-sm font-bold text-text-main hover:underline">Lihat Semua Log</a>
            </div>
        </div>
    </div>
</div>
@endsection

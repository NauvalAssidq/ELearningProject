@extends('layouts.app')

@section('title', 'Hasil Tes Penempatan')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 text-center">
    @php
        $levelColors = [
            'pemula' => 'from-green-500 to-emerald-600',
            'menengah' => 'from-yellow-500 to-orange-600',
            'mahir' => 'from-red-500 to-pink-600',
        ];
        $levelEmojis = [
            'pemula' => '🌱',
            'menengah' => '🌿',
            'mahir' => '🌳',
        ];
        $levelLabels = [
            'pemula' => 'Pemula (Beginner)',
            'menengah' => 'Menengah (Intermediate)',
            'mahir' => 'Mahir (Advanced)',
        ];
    @endphp

    <div class="w-24 h-24 bg-gradient-to-br {{ $levelColors[$user->skill_level] ?? 'from-gray-500 to-gray-600' }} rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
        <span class="text-5xl">{{ $levelEmojis[$user->skill_level] ?? '📚' }}</span>
    </div>
    
    <h1 class="text-4xl font-bold text-text-main mb-4">Selamat!</h1>
    <p class="text-lg text-text-muted mb-8">Anda telah menyelesaikan tes penempatan</p>

    <div class="bg-white border border-border p-8 mb-8">
        <div class="mb-6">
            <p class="text-sm text-text-muted uppercase tracking-widest mb-2">Level Anda</p>
            <p class="text-3xl font-bold bg-gradient-to-r {{ $levelColors[$user->skill_level] ?? 'from-gray-500 to-gray-600' }} bg-clip-text text-transparent">
                {{ $levelLabels[$user->skill_level] ?? 'Belum Ditentukan' }}
            </p>
        </div>

        @if(isset($result['correct']) && isset($result['total']))
        <div class="grid grid-cols-3 gap-4 pt-6 border-t border-border">
            <div>
                <p class="text-3xl font-bold text-text-main">{{ $result['correct'] }}</p>
                <p class="text-xs text-text-muted">Jawaban Benar</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-text-main">{{ $result['total'] }}</p>
                <p class="text-xs text-text-muted">Total Soal</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-text-main">{{ $result['percentage'] }}%</p>
                <p class="text-xs text-text-muted">Skor</p>
            </div>
        </div>
        @endif
    </div>

    <div class="bg-blue-50 border border-blue-200 p-6 mb-8 text-left">
        <h3 class="font-bold text-blue-900 mb-2">Apa Selanjutnya?</h3>
        <p class="text-blue-800 text-sm">
            Berdasarkan hasil tes, Anda akan melihat modul-modul yang sesuai dengan level <strong>{{ ucfirst($user->skill_level) }}</strong>. 
            Daftar ke modul yang ingin Anda pelajari dan mulai perjalanan belajar Anda!
        </p>
    </div>

    <a href="{{ route('dashboard') }}" 
       class="inline-flex items-center justify-center gap-3 bg-black text-white px-8 py-4 font-bold text-lg hover:bg-gray-800 transition-all">
        Lanjut ke Dashboard
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
        </svg>
    </a>
</div>
@endsection

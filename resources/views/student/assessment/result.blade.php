@extends('layouts.app')

@section('title', 'Hasil Asesmen')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    @if($attempt->passed)
        <!-- SUCCESS STATE -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-black mb-2">Selamat!</h1>
            <p class="text-xl text-gray-600">Anda berhasil naik level.</p>
        </div>

        <div class="bg-white border border-gray-200 p-8 text-center mb-8">
            <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">Level Baru Anda</p>
            <p class="text-5xl font-black text-black mb-4">{{ ucfirst($user->skill_level) }}</p>
            <p class="text-gray-600">Anda sekarang dapat mengakses modul level {{ ucfirst($user->skill_level) }}.</p>
        </div>

        <div class="bg-green-50 border border-green-200 p-6 mb-8">
            <div class="flex justify-between items-center">
                <span class="text-green-800 font-medium">Skor Anda</span>
                <span class="text-2xl font-bold text-green-600">{{ $attempt->score }}%</span>
            </div>
            <p class="text-green-700 text-sm mt-2">{{ $correctCount }}/{{ $totalQuestions }} jawaban benar</p>
        </div>

    @else
        <!-- FAIL STATE -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-black mb-2">Belum Berhasil</h1>
            <p class="text-xl text-gray-600">Anda belum mencapai passing grade.</p>
        </div>

        <div class="bg-white border border-gray-200 p-8 text-center mb-8">
            <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">Skor Anda</p>
            <p class="text-5xl font-black text-red-600 mb-4">{{ $attempt->score }}%</p>
            <p class="text-gray-600">Passing grade: 75% ({{ $correctCount }}/{{ $totalQuestions }} benar)</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 p-6 mb-8">
            <div class="flex items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600 shrink-0">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <div>
                    <p class="text-yellow-800 font-medium">Masa Cooldown Aktif</p>
                    <p class="text-yellow-700 text-sm">Anda dapat mengulang asesmen ini dalam <strong>7 hari</strong>.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Action -->
    <div class="flex gap-4">
        <a href="{{ route('dashboard') }}" 
           class="flex-1 text-center py-3 border border-gray-200 font-bold text-sm uppercase tracking-wider hover:bg-gray-50 transition-colors">
            Kembali ke Dashboard
        </a>
        <a href="{{ route('student.modules.index') }}" 
           class="flex-1 text-center py-3 bg-black text-white font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition-colors">
            Lihat Modul
        </a>
    </div>

</div>
@endsection

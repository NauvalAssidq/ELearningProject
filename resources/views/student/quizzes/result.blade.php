@extends('layouts.app')

@section('title', 'Hasil Kuis - ' . $lesson->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => $module->title, 'url' => route('student.modules.show', $module), 'truncate' => true],
        ['label' => 'Hasil Kuis'],
    ]" class="mb-8 justify-center" />
    @php
        $bgColor = $passed ? 'from-green-500 to-emerald-600' : 'from-red-500 to-pink-600';
        $emoji = $passed ? '🎉' : '📚';
    @endphp

    <div class="w-24 h-24 bg-gradient-to-br {{ $bgColor }} rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
        <span class="text-5xl">{{ $emoji }}</span>
    </div>
    
    <h1 class="text-4xl font-bold text-text-main mb-4">
        {{ $passed ? 'Selamat!' : 'Coba Lagi' }}
    </h1>
    <p class="text-lg text-text-muted mb-8">
        {{ $passed ? 'Anda telah lulus kuis ini.' : 'Anda belum mencapai nilai minimum (60%).' }}
    </p>

    <div class="bg-white border border-border p-8 mb-8">
        <div class="mb-6">
            <p class="text-sm text-text-muted uppercase tracking-widest mb-2">Skor Anda</p>
            <p class="text-6xl font-bold {{ $passed ? 'text-green-600' : 'text-red-600' }}">{{ $score }}%</p>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-border">
            <div>
                <p class="text-3xl font-bold text-text-main">{{ $correct }}</p>
                <p class="text-xs text-text-muted">Jawaban Benar</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-text-main">{{ $total }}</p>
                <p class="text-xs text-text-muted">Total Soal</p>
            </div>
        </div>
    </div>

    @if($passed)
        <div class="bg-green-50 border border-green-200 p-6 mb-8 text-left">
            <div class="flex items-start gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 mt-0.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div>
                    <h3 class="font-bold text-green-900">Pelajaran Selesai!</h3>
                    <p class="text-green-800 text-sm">
                        Anda telah berhasil menyelesaikan pelajaran "{{ $lesson->title }}". 
                        Lanjutkan ke pelajaran berikutnya untuk melanjutkan progres Anda.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 p-6 mb-8 text-left">
            <div class="flex items-start gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600 mt-0.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    <h3 class="font-bold text-yellow-900">Belum Lulus</h3>
                    <p class="text-yellow-800 text-sm">
                        Pelajari kembali materi pelajaran "{{ $lesson->title }}" dan coba lagi. 
                        Anda membutuhkan skor minimal 60% untuk lulus.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-center gap-4">
        @if(!$passed)
            <a href="{{ route('student.quizzes.start', [$module, $lesson]) }}" 
               class="inline-flex items-center justify-center gap-2 bg-black text-white px-6 py-3 font-bold hover:bg-gray-800 transition-all">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="1 4 1 10 7 10"></polyline>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                </svg>
                Coba Lagi
            </a>
        @endif
        <a href="{{ route('student.lessons.show', [$module, $lesson]) }}" 
           class="inline-flex items-center justify-center gap-2 border border-border px-6 py-3 font-bold hover:bg-surface-off transition-all">
            Kembali ke Pelajaran
        </a>
        <a href="{{ route('student.modules.show', $module) }}" 
           class="inline-flex items-center justify-center gap-2 border border-border px-6 py-3 font-bold hover:bg-surface-off transition-all">
            Lihat Modul
        </a>
    </div>
</div>
@endsection

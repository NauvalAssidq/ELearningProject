@extends('layouts.app')

@section('title', 'Tes Penempatan')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M12 20h9"></path>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-text-main mb-4">Tes Penempatan</h1>
        <p class="text-lg text-text-muted max-w-lg mx-auto">
            Sebelum memulai pembelajaran, kami perlu mengetahui level kemampuan Anda untuk memberikan materi yang sesuai.
        </p>
    </div>

    <div class="bg-white border border-border p-8 mb-8">
        <h2 class="text-xl font-bold mb-4">Tentang Tes Ini</h2>
        <ul class="space-y-3 text-text-muted">
            <li class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 mt-0.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span><strong>{{ $questionCount }} pertanyaan</strong> pilihan ganda (acak dari bank soal)</span>
            </li>
            <li class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 mt-0.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Waktu pengerjaan: <strong>{{ $timeLimit }} menit</strong></span>
            </li>
            <li class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 mt-0.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Hasil akan menentukan level: <strong>Pemula</strong>, <strong>Menengah</strong>, atau <strong>Mahir</strong></span>
            </li>
            <li class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600 mt-0.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>Tes hanya dilakukan <strong>satu kali</strong></span>
            </li>
        </ul>
    </div>

    <div class="text-center">
        <a href="{{ route('placement.start') }}" 
           class="inline-flex items-center justify-center gap-3 bg-black text-white px-8 py-4 font-bold text-lg hover:bg-gray-800 transition-all">
            Mulai Tes Sekarang
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>
</div>
@endsection

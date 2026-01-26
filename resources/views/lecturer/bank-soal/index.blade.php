@extends('layouts.app')

@section('title', 'Bank Soal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Bank Soal'],
    ]" />

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-text-main">Bank Soal</h1>
            <p class="text-text-muted mt-1">Koleksi pertanyaan untuk tes penempatan. Setiap siswa akan mendapatkan 10 soal acak.</p>
        </div>
        <a href="{{ route('lecturer.bank-soal.create') }}" 
           class="inline-flex items-center gap-2 bg-black text-white px-6 py-3 font-bold hover:bg-gray-800 transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Pertanyaan
        </a>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('lecturer.bank-soal.index') }}" 
           class="px-4 py-2 text-sm font-bold border {{ !request('difficulty') && !request('mine') ? 'bg-black text-white border-black' : 'border-border hover:border-black' }}">
            Semua
        </a>
        <a href="{{ route('lecturer.bank-soal.index', ['difficulty' => 'easy']) }}" 
           class="px-4 py-2 text-sm font-bold border {{ request('difficulty') === 'easy' ? 'bg-green-500 text-white border-green-500' : 'border-border hover:border-black' }}">
            Mudah
        </a>
        <a href="{{ route('lecturer.bank-soal.index', ['difficulty' => 'medium']) }}" 
           class="px-4 py-2 text-sm font-bold border {{ request('difficulty') === 'medium' ? 'bg-yellow-500 text-white border-yellow-500' : 'border-border hover:border-black' }}">
            Sedang
        </a>
        <a href="{{ route('lecturer.bank-soal.index', ['difficulty' => 'hard']) }}" 
           class="px-4 py-2 text-sm font-bold border {{ request('difficulty') === 'hard' ? 'bg-red-500 text-white border-red-500' : 'border-border hover:border-black' }}">
            Sulit
        </a>
        <a href="{{ route('lecturer.bank-soal.index', ['mine' => '1']) }}" 
           class="px-4 py-2 text-sm font-bold border {{ request('mine') === '1' ? 'bg-blue-500 text-white border-blue-500' : 'border-border hover:border-black' }}">
            Milik Saya
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white border border-border p-4">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-1">Total Pertanyaan</p>
            <p class="text-3xl font-bold">{{ \App\Models\BankSoal::count() }}</p>
        </div>
        <div class="bg-white border border-border p-4">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-1">Aktif</p>
            <p class="text-3xl font-bold text-green-600">{{ \App\Models\BankSoal::active()->count() }}</p>
        </div>
        <div class="bg-white border border-border p-4">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-1">Milik Anda</p>
            <p class="text-3xl font-bold text-blue-600">{{ \App\Models\BankSoal::where('created_by', auth()->id())->count() }}</p>
        </div>
        <div class="bg-white border border-border p-4">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-1">Soal per Siswa</p>
            <p class="text-3xl font-bold">10</p>
        </div>
    </div>

    {{-- Questions List --}}
    <div class="bg-white border border-border">
        @if($questions->count() > 0)
            <div class="divide-y divide-border">
                @foreach($questions as $question)
                    <div class="p-6 hover:bg-surface-off transition-colors group">
                        <div class="flex items-start gap-4">
                            {{-- Difficulty Badge --}}
                            @php
                                $difficultyColors = [
                                    'easy' => 'bg-green-100 text-green-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'hard' => 'bg-red-100 text-red-800',
                                ];
                                $difficultyLabels = [
                                    'easy' => 'Mudah',
                                    'medium' => 'Sedang',
                                    'hard' => 'Sulit',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-bold uppercase {{ $difficultyColors[$question->difficulty] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $difficultyLabels[$question->difficulty] ?? $question->difficulty }}
                            </span>

                            {{-- Question Content --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-text-main mb-2 {{ !$question->is_active ? 'line-through opacity-50' : '' }}">
                                    {{ Str::limit($question->question_text, 150) }}
                                </p>
                                <div class="flex flex-wrap items-center gap-4 text-xs text-text-muted">
                                    <span>{{ $question->options->count() }} pilihan</span>
                                    <span>•</span>
                                    <span>Oleh: {{ $question->creator->name ?? 'Unknown' }}</span>
                                    @if(!$question->is_active)
                                        <span class="px-2 py-0.5 bg-gray-200 text-gray-600 rounded uppercase font-bold">Nonaktif</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('lecturer.bank-soal.toggle-active', $question) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 hover:bg-gray-100 {{ $question->is_active ? 'text-green-600' : 'text-gray-400' }}" title="{{ $question->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            @if($question->is_active)
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                            @else
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                                <line x1="9" y1="9" x2="15" y2="15"></line>
                                            @endif
                                        </svg>
                                    </button>
                                </form>
                                <a href="{{ route('lecturer.bank-soal.edit', $question) }}" class="p-2 hover:bg-gray-100 text-text-muted hover:text-black">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('lecturer.bank-soal.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pertanyaan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 text-text-muted hover:text-red-600">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-border">
                {{ $questions->links() }}
            </div>
        @else
            <div class="p-12 text-center text-text-muted">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-3 opacity-50">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                <p class="mb-4">Belum ada pertanyaan di Bank Soal.</p>
                <a href="{{ route('lecturer.bank-soal.create') }}" class="text-sm font-bold text-accent hover:underline">+ Tambah Pertanyaan Pertama</a>
            </div>
        @endif
    </div>
</div>
@endsection

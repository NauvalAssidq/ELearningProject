@extends('layouts.app')

@section('title', $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 border-b border-border">
        <div class="flex-1">
            {{-- Breadcrumb --}}
            <x-breadcrumb :items="[
                ['label' => 'Dasbor', 'url' => route('dashboard')],
                ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
                ['label' => $module->title, 'truncate' => true],
            ]"/>
            <h1 class="text-4xl font-bold tracking-tight text-text-main mb-4">{{ $module->title }}</h1>
            <p class="text-lg text-text-muted max-w-3xl leading-relaxed">{{ $module->description }}</p>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('lecturer.modules.learners', $module) }}" class="px-4 py-2 border border-border text-xs font-bold uppercase tracking-wider hover:bg-gray-50 flex items-center gap-2">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Peserta
            </a>
            <a href="{{ route('lecturer.submissions.index', $module) }}" class="px-4 py-2 border border-border text-xs font-bold uppercase tracking-wider hover:bg-gray-50 flex items-center gap-2">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <polyline points="16 13 12 17 8 13"></polyline>
                    <line x1="12" y1="17" x2="12" y2="11"></line>
                </svg>
                Submissions
            </a>
            <a href="{{ route('lecturer.modules.edit', $module) }}" class="px-4 py-2 border border-border text-xs font-bold uppercase tracking-wider hover:bg-gray-50">Edit Modul</a>
            <form action="{{ route('lecturer.modules.destroy', $module) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus modul ini? Semua pelajaran di dalamnya akan ikut terhapus.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-border text-xs font-bold uppercase tracking-wider hover:bg-red-50 text-red-600">Hapus</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Lessons Section -->
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold uppercase tracking-widest text-text-main">Daftar Pelajaran</h2>
            <div class="flex gap-2">
                <a href="{{ route('lecturer.quizzes.create', $module) }}" class="border border-border px-4 py-2 font-bold uppercase tracking-wider text-xs hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    Buat Kuis
                </a>
                <a href="{{ route('lecturer.lessons.create', $module) }}" class="bg-black text-white px-4 py-2 font-bold uppercase tracking-wider text-xs hover:bg-accent transition-colors">
                    + Tambah Pelajaran
                </a>
            </div>
        </div>

        <div class="bg-white border border-border">
            <ul class="divide-y divide-border">
                @forelse($module->lessons as $lesson)
                    @php
                        $hasQuiz = $lesson->quiz()->exists();
                    @endphp
                    <li class="p-4 hover:bg-surface-off transition-colors group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center justify-center w-8 h-8 bg-surface border border-border text-xs font-bold text-text-muted">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <h4 class="font-bold text-lg group-hover:text-accent transition-colors">
                                        <a href="{{ route('lecturer.lessons.show', [$module, $lesson]) }}">{{ $lesson->title }}</a>
                                    </h4>
                                    <div class="flex items-center gap-3 text-xs text-text-muted">
                                        @if($lesson->is_preview)
                                            <span class="bg-green-100 text-green-800 px-1.5 py-0.5 rounded uppercase tracking-wider font-bold" style="font-size: 0.65rem;">Preview</span>
                                        @endif
                                        <span>{{ $lesson->duration ?? 0 }} Menit</span>
                                        
                                        {{-- Quiz Status --}}
                                        @if($hasQuiz)
                                            <span class="flex items-center gap-1 text-purple-600 font-semibold">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                                Kuis
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1 text-yellow-600">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                </svg>
                                                Belum ada kuis
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($hasQuiz)
                                    <a href="{{ route('lecturer.quizzes.edit', [$module, $lesson->quiz]) }}" 
                                       class="text-xs font-bold uppercase text-purple-600 hover:text-purple-800 px-2 flex items-center gap-1">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                        Edit Kuis
                                    </a>
                                @else
                                    <a href="{{ route('lecturer.quizzes.create', $module) }}?lesson_id={{ $lesson->id }}" 
                                       class="text-xs font-bold uppercase text-purple-600 hover:text-purple-800 px-2 flex items-center gap-1">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Kuis
                                    </a>
                                @endif
                                <a href="{{ route('lecturer.lessons.edit', [$module, $lesson]) }}" class="text-xs font-bold uppercase text-text-muted hover:text-black px-2">Edit</a>
                                <form action="{{ route('lecturer.lessons.destroy', [$module, $lesson]) }}" method="POST" onsubmit="return confirm('Hapus pelajaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold uppercase text-red-600 hover:text-red-800 px-2">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="p-8 text-center text-text-muted">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-3 opacity-50">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        <p class="mb-3">Belum ada pelajaran dalam modul ini.</p>
                        <a href="{{ route('lecturer.lessons.create', $module) }}" class="text-sm font-bold text-accent hover:underline">+ Tambah Pelajaran Pertama</a>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

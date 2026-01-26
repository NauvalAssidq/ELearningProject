@extends('layouts.app')

@section('title', 'Dasbor Pembelajaran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
    {{-- Header with Skill Level Badge --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-4xl font-semibold text-text-main mb-2">Selamat Datang, {{ $user->name }}!</h2>
            <p class="text-text-muted">Lacak pencapaian dan kemajuan pembelajaran Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            @php
                $levelColors = [
                    'pemula' => 'bg-green-100 text-green-800 border-green-300',
                    'menengah' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'mahir' => 'bg-red-100 text-red-800 border-red-300',
                ];
            @endphp
            <span class="px-4 py-2 text-sm font-bold uppercase tracking-wide border {{ $levelColors[$user->skill_level] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                Level: {{ ucfirst($user->skill_level ?? 'N/A') }}
            </span>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 border-t border-l border-border bg-white">
        <div class="p-8 border-r border-b border-border">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-4">Modul Tersedia</p>
            <p class="text-5xl font-light">{{ $totalModules }}</p>
        </div>
        <div class="p-8 border-r border-b border-border">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-4">Modul Diikuti</p>
            <p class="text-5xl font-light text-accent">{{ $enrolledCount }}</p>
        </div>
        <div class="p-8 border-r border-b border-border">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-4">Nilai Rata-rata</p>
            <p class="text-5xl font-light">{{ $grade ?: '—' }}</p>
        </div>
    </div>

    {{-- Enrolled Modules with Progress --}}
    @if($enrolledModules->count() > 0)
    <div>
        <div class="flex justify-between items-end mb-6">
            <h3 class="text-lg font-semibold uppercase tracking-wide">Lanjutkan Belajar</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($enrolledModules as $module)
            @php
                $totalLessons = $module->lessons->count();
                $completedLessons = $module->lessons->filter(fn($l) => $l->isCompletedBy($user))->count();
                $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                $moduleLevelColors = [
                    'pemula' => 'bg-green-500',
                    'menengah' => 'bg-yellow-500',
                    'mahir' => 'bg-red-500',
                ];
            @endphp
            <a href="{{ route('student.modules.show', $module) }}" 
               class="bg-white border border-border hover:border-black transition-all group overflow-hidden flex flex-col ring-2 ring-blue-500 ring-offset-2">
                {{-- Module Image - Same as module index --}}

                
                <div class="p-6 flex-1 flex flex-col">
                    <h4 class="text-xl font-bold group-hover:text-accent transition-colors mb-2">{{ $module->title }}</h4>
                    <p class="text-text-muted text-sm mb-4 line-clamp-2 flex-1">{{ $module->description }}</p>
                    
                    {{-- Progress Bar --}}
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-text-muted mb-1">
                            <span>{{ $completedLessons }}/{{ $totalLessons }} pelajaran</span>
                            <span class="font-bold">{{ $progress }}%</span>
                        </div>
                        <div class="h-2 bg-gray-200 overflow-hidden">
                            <div class="h-full bg-black transition-all duration-500" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-3 text-text-muted">
                            <span class="flex items-center gap-1">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                                {{ $module->lessons_count }} Pelajaran
                            </span>
                        </div>
                        <span class="font-bold text-accent group-hover:translate-x-1 transition-transform flex items-center gap-1">
                            Lanjutkan Belajar
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Available Modules --}}
    <div>
        <div class="flex justify-between items-end mb-6">
            <h3 class="text-lg font-semibold uppercase tracking-wide">Modul Level {{ ucfirst($user->skill_level) }}</h3>
            <a href="{{ route('student.modules.index') }}" class="text-xs font-bold underline hover:text-accent">Lihat Semua</a>
        </div>

        @if($availableModules->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($availableModules->take(6) as $module)
            @php
                $moduleLevelColors = [
                    'pemula' => 'bg-green-500',
                    'menengah' => 'bg-yellow-500',
                    'mahir' => 'bg-red-500',
                ];
            @endphp
            <div class="bg-white border border-border hover:border-black transition-all overflow-hidden group flex flex-col">
                {{-- Module Image - Same as module index --}}
                <div class="aspect-video overflow-hidden bg-surface-off relative">
                    @if($module->image)
                        <img src="{{ asset('storage/' . $module->image) }}" alt="{{ $module->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-gray-400">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    {{-- Skill Level Badge --}}
                    <span class="absolute top-3 left-3 px-2 py-1 text-xs font-bold uppercase tracking-wider text-white {{ $moduleLevelColors[$module->skill_level] ?? 'bg-gray-500' }}">
                        {{ $module->skill_level ?? 'N/A' }}
                    </span>
                </div>
                
                <div class="p-6 flex-1 flex flex-col">
                    <h4 class="font-bold text-xl mb-2 group-hover:text-accent transition-colors line-clamp-2">{{ $module->title }}</h4>
                    <p class="text-text-muted text-sm mb-4 line-clamp-2 flex-1">{{ $module->description }}</p>
                    
                    <div class="flex items-center gap-4 text-sm text-text-muted mb-4">
                        <span class="flex items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            {{ $module->lessons_count }} Pelajaran
                        </span>
                        @if($module->quizzes_count ?? 0)
                        <span class="flex items-center gap-1 text-blue-600">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Kuis
                        </span>
                        @endif
                    </div>
                    
                    @if($user->isEnrolledIn($module))
                        <a href="{{ route('student.modules.show', $module) }}" 
                           class="w-full bg-black text-white py-3 font-bold text-sm text-center hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                            Lanjutkan Belajar
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    @else
                        <form action="{{ route('student.modules.enroll', $module) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-black text-white py-3 font-bold text-sm hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                                Daftar Gratis
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center text-text-muted border border-dashed border-border">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 opacity-50">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            Belum ada modul tersedia untuk level {{ ucfirst($user->skill_level) }}.
        </div>
        @endif
    </div>
</div>
@endsection

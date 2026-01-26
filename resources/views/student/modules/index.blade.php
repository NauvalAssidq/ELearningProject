@extends('layouts.app')

@section('title', 'Modul Pelajaran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Modul Pembelajaran'],
    ]" />

    {{-- Header with Level Badge --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-text-main">Modul Pembelajaran</h1>
            <p class="text-text-muted mt-2">Jelajahi modul yang tersedia untuk level Anda</p>
        </div>
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

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 font-medium">
            {{ session('warning') }}
        </div>
    @endif

    {{-- Modules Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
            @php
                $isEnrolled = $user->isEnrolledIn($module);
            @endphp
            <div class="group bg-white border border-border overflow-hidden hover:border-black transition-all duration-300 flex flex-col {{ $isEnrolled ? 'ring-2 ring-blue-500 ring-offset-2' : '' }}">
                {{-- Module Image --}}
                <div class="aspect-video overflow-hidden bg-surface-off relative">
                    @if($module->image)
                        <img src="{{ asset($module->image) }}" alt="{{ $module->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-gray-400">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    {{-- Enrolled Badge --}}
                    @if($isEnrolled)
                        <span class="absolute top-3 right-3 px-2 py-1 bg-blue-500 text-white text-xs font-bold uppercase tracking-wider">
                            Terdaftar
                        </span>
                    @endif
                    
                    {{-- Skill Level Badge --}}
                    @php
                        $moduleLevelColors = [
                            'pemula' => 'bg-green-500',
                            'menengah' => 'bg-yellow-500',
                            'mahir' => 'bg-red-500',
                        ];
                    @endphp
                    <span class="absolute top-3 left-3 px-2 py-1 text-xs font-bold uppercase tracking-wider text-white {{ $moduleLevelColors[$module->skill_level] ?? 'bg-gray-500' }}">
                        {{ $module->skill_level ?? 'N/A' }}
                    </span>
                </div>
                
                {{-- Module Info --}}
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="font-bold text-xl mb-2 group-hover:text-accent transition-colors line-clamp-2">{{ $module->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2 mb-4 flex-1">{{ $module->description }}</p>
                    
                    {{-- Meta Info --}}
                    <div class="flex items-center gap-4 text-xs text-text-muted font-medium mb-4">
                        <span class="flex items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            {{ $module->lessons_count }} Pelajaran
                        </span>
                        @if($module->quiz)
                            <span class="flex items-center gap-1 text-purple-600 font-semibold">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                Kuis
                            </span>
                        @endif
                    </div>
                    
                    {{-- Action Button --}}
                    <div class="pt-4 border-t border-border">
                        @if($isEnrolled)
                            <a href="{{ route('student.modules.show', $module) }}" 
                               class="block text-center py-3 bg-black text-white font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition-colors">
                                Lanjutkan Belajar →
                            </a>
                        @else
                            <div class="flex gap-2">
                                <a href="{{ route('student.modules.show', $module) }}" 
                                   class="flex-1 text-center py-3 border border-border font-bold text-sm uppercase tracking-wider hover:bg-surface-off transition-colors">
                                    Lihat
                                </a>
                                <form action="{{ route('student.modules.enroll', $module) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full py-3 bg-accent text-white font-bold text-sm uppercase tracking-wider hover:bg-black transition-colors">
                                        Daftar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-border p-12 text-center">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-text-muted">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <p class="text-text-muted text-lg mb-2">Belum ada modul tersedia untuk level {{ ucfirst($user->skill_level) }}</p>
                <p class="text-text-muted text-sm">Silakan hubungi dosen untuk menambahkan modul baru.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

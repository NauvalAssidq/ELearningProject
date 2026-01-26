@extends('layouts.app')

@section('title', 'Manajemen Modul')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Manajemen Modul'],
    ]" />

    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h1 class="text-4xl font-bold tracking-tight text-text-main">Modul</h1>
            <p class="text-text-muted mt-2">Kelola materi pembelajaran dan kurikulum.</p>
        </div>
        <a href="{{ route('lecturer.modules.create') }}" class="inline-flex items-center gap-2 bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-800 transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Buat Modul Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
            <div class="group bg-white border border-border flex flex-col h-full hover:border-black transition-colors">
                @if($module->image)
                    <div class="aspect-video w-full bg-gray-100 overflow-hidden border-b border-border relative">
                        <img src="{{ asset($module->image) }}" alt="{{ $module->title }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300">
                        {{-- Skill Level Badge --}}
                        @php
                            $levelColors = [
                                'pemula' => 'bg-green-500',
                                'menengah' => 'bg-yellow-500',
                                'mahir' => 'bg-red-500',
                            ];
                        @endphp
                        <span class="absolute top-3 left-3 px-2 py-1 text-xs font-bold uppercase tracking-wider text-white {{ $levelColors[$module->skill_level] ?? 'bg-gray-500' }}">
                            {{ $module->skill_level ?? 'N/A' }}
                        </span>
                    </div>
                @else
                    <div class="aspect-video w-full bg-surface-off border-b border-border flex items-center justify-center relative">
                        <span class="text-text-muted text-xs uppercase tracking-widest">No Image</span>
                        @php
                            $levelColors = [
                                'pemula' => 'bg-green-500',
                                'menengah' => 'bg-yellow-500',
                                'mahir' => 'bg-red-500',
                            ];
                        @endphp
                        <span class="absolute top-3 left-3 px-2 py-1 text-xs font-bold uppercase tracking-wider text-white {{ $levelColors[$module->skill_level] ?? 'bg-gray-500' }}">
                            {{ $module->skill_level ?? 'N/A' }}
                        </span>
                    </div>
                @endif
                
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold mb-2 leading-tight group-hover:text-accent transition-colors">
                        <a href="{{ route('lecturer.modules.show', $module) }}">
                            {{ $module->title }}
                        </a>
                    </h3>
                    <p class="text-text-muted text-sm line-clamp-3 mb-6 flex-1">
                        {{ $module->description }}
                    </p>
                    
                    <div class="pt-4 border-t border-border space-y-3">
                        <div class="flex justify-between items-center text-xs font-medium text-text-muted">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center gap-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                    </svg>
                                    {{ $module->lessons_count }} Pelajaran
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                    {{ $module->enrollments_count ?? 0 }} Siswa
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('lecturer.modules.show', $module) }}" class="block text-center py-2 border border-border text-xs font-bold uppercase tracking-wider hover:bg-black hover:text-white hover:border-black transition-colors">
                            Kelola →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-text-muted bg-white border border-border border-dashed">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-3 opacity-50">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <p class="mb-4">Belum ada modul yang dibuat.</p>
                <a href="{{ route('lecturer.modules.create') }}" class="text-sm font-bold text-accent hover:underline">+ Buat Modul Pertama</a>
            </div>
        @endforelse
    </div>
</div>
@endsection

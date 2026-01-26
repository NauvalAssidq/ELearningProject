@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
        ['label' => $module->title, 'url' => route('lecturer.modules.show', $module), 'truncate' => true],
        ['label' => $lesson->title, 'truncate' => true],
    ]" class="pb-2" />
    
    <div class="bg-white border border-border">
        <!-- Media Section -->
        @if($lesson->media_url)
            <div class="w-full aspect-video bg-black" style="max-height: 70vh;">
                @php
                    $extension = strtolower(pathinfo($lesson->media_url, PATHINFO_EXTENSION));
                    $isVideo = in_array($extension, ['mp4', 'mov', 'webm', 'ogg']);
                @endphp
                
                @if($isVideo)
                    <video controls class="w-full h-full" controlsList="nodownload">
                        <source src="{{ asset($lesson->media_url) }}" type="video/{{ $extension }}">
                        Browser Anda tidak mendukung video.
                    </video>
                @else
                    <img src="{{ asset($lesson->media_url) }}" alt="{{ $lesson->title }}" class="w-full h-full object-contain">
                @endif
            </div>
        @endif

        <div class="p-4 sm:p-8 md:p-12 space-y-6 sm:space-y-8">
            <div class="border-b border-border pb-4 sm:pb-6">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight text-text-main mb-2">{{ $lesson->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-text-muted">
                    <span>{{ $lesson->duration ?? '~' }} Menit</span>
                    @if($lesson->is_preview)
                        <span class="bg-green-100 text-green-800 px-2 py-0.5 font-bold uppercase text-xs">Preview</span>
                    @endif
                </div>
            </div>

            @if($lesson->content)
                <div class="lesson-content">
                    {!! $lesson->content !!}
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 p-12 text-center">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-gray-400">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <p class="text-gray-500 mb-4">Konten pelajaran belum ditambahkan.</p>
                    <a href="{{ route('lecturer.lessons.edit', [$module, $lesson]) }}" class="inline-block bg-black text-white px-6 py-2 font-semibold uppercase text-sm hover:bg-gray-800">
                        Tambah Konten
                    </a>
                </div>
            @endif
        </div>

        <!-- Lesson Navigation Footer -->
        <div class="p-6 bg-surface-off border-t border-border">
            @php
                $lessons = $module->lessons->sortBy('order');
                $currentIndex = $lessons->search(function($item) use ($lesson) {
                    return $item->id === $lesson->id;
                });
                $prevLesson = $currentIndex > 0 ? $lessons->values()[$currentIndex - 1] : null;
                $nextLesson = $currentIndex < $lessons->count() - 1 ? $lessons->values()[$currentIndex + 1] : null;
            @endphp

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                <div class="w-full sm:w-auto">
                    @if($prevLesson)
                        <a href="{{ route('lecturer.lessons.show', [$module, $prevLesson]) }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 border border-border bg-white text-text-main hover:bg-gray-50 transition-colors">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <span class="text-sm font-medium">Sebelumnya</span>
                        </a>
                    @else
                        <div class="w-full sm:w-auto"></div>
                    @endif
                </div>

                <div class="text-sm text-text-muted font-medium">
                    Pelajaran {{ $currentIndex + 1 }} dari {{ $lessons->count() }}
                </div>

                <div class="w-full sm:w-auto">
                    @if($nextLesson)
                        <a href="{{ route('lecturer.lessons.show', [$module, $nextLesson]) }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-black text-white hover:bg-gray-800 transition-colors">
                            <span class="text-sm font-medium">Selanjutnya</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Edit Button -->
            <div class="flex justify-end">
                <a href="{{ route('lecturer.lessons.edit', [$module, $lesson]) }}" 
                   class="px-6 py-3 border border-border text-sm font-bold uppercase tracking-wider hover:bg-gray-50 transition-colors">
                    Edit Pelajaran
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
@include('lecturer.lessons.css')
@endpush
@endsection

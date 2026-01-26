@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => \$module->title, 'url' => route('student.modules.show', \$module), 'truncate' => true],
        ['label' => \$lesson->title, 'truncate' => true],
    ]" class="mb-6" />


    <!-- Sticky Progress Bar -->
    @php
        $lessons = $module->lessons->sortBy('order');
        $currentIndex = $lessons->search(function($item) use ($lesson) {
            return $item->id === $lesson->id;
        });
        $progress = (($currentIndex + 1) / $lessons->count()) * 100;
    @endphp
    <div class="sticky top-0 bg-white border-b border-border z-10 shadow-sm">
        <div class="h-1 bg-gray-200">
            <div class="h-1 bg-blue-600 transition-all duration-300" style="width: {{ $progress }}%"></div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white border border-border overflow-hidden">
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

        <!-- Content Section -->
        <div class="p-4 sm:p-8 md:p-12">
            <!-- Title & Meta -->
            <div class="mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-border">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-text-main mb-3 sm:mb-4 leading-tight">{{ $lesson->title }}</h1>
                
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    @if($lesson->is_preview)
                        <span class="bg-green-50 text-green-700 px-3 py-1 border border-green-200 font-medium uppercase text-xs tracking-wider">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            Preview Gratis
                        </span>
                    @endif
                    
                    @if($lesson->duration)
                        <div class="flex items-center gap-2 text-text-muted">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>{{ $lesson->duration }} Menit</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lesson Content -->
            @if($lesson->content)
                <div class="lesson-content">
                    {!! $lesson->content !!}
                </div>
            @else
                <p class="text-text-muted italic">Belum ada konten untuk pelajaran ini.</p>
            @endif
        </div>

        {{-- Quiz Section --}}
        @php
            $quiz = $lesson->quiz;
            $user = auth()->user();
            $progress = $lesson->getProgressFor($user);
            $isCompleted = $progress && $progress->is_completed;
            $quizScore = $progress?->quizAttempt?->score;
        @endphp
        
        @if($quiz)
            <div class="p-6 border-t border-border {{ $isCompleted ? 'bg-green-50' : 'bg-yellow-50' }}">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        @if($isCompleted)
                            <div class="flex items-center gap-2 text-green-700 font-bold mb-1">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Kuis Selesai!
                            </div>
                            <p class="text-sm text-green-600">
                                Anda telah lulus kuis ini dengan skor <span class="font-bold">{{ $quizScore ?? 'N/A' }}%</span>
                            </p>
                        @else
                            <div class="flex items-center gap-2 text-yellow-800 font-bold mb-1">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                {{ $quiz->title }}
                            </div>
                            <p class="text-sm text-yellow-700">
                                Selesaikan kuis untuk menyelesaikan pelajaran ini. ({{ $quiz->questions()->count() }} soal, {{ $quiz->time_limit }} menit)
                            </p>
                        @endif
                    </div>
                    
                    <div class="flex gap-2">
                        @if($isCompleted)
                            <a href="{{ route('student.quizzes.start', [$module, $lesson]) }}" 
                               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-green-300 text-green-700 font-bold text-sm hover:bg-green-100 transition-colors">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M23 4v6h-6"></path>
                                    <path d="M1 20v-6h6"></path>
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                </svg>
                                Ulangi Kuis
                            </a>
                        @else
                            <a href="{{ route('student.quizzes.start', [$module, $lesson]) }}" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-black text-white font-bold text-sm hover:bg-gray-800 transition-colors">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                                Ambil Kuis
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Navigation Footer --}}
        <div class="p-6 bg-surface-off border-t border-border flex flex-col sm:flex-row justify-between items-center gap-4">
            @php
                $lessons = $module->lessons->sortBy('order');
                $currentIndex = $lessons->search(function($item) use ($lesson) {
                    return $item->id === $lesson->id;
                });
                $prevLesson = $currentIndex > 0 ? $lessons->values()[$currentIndex - 1] : null;
                $nextLesson = $currentIndex < $lessons->count() - 1 ? $lessons->values()[$currentIndex + 1] : null;
                
                // Check if current lesson has a quiz and if it's completed
                $canProceed = !$quiz || $isCompleted;
            @endphp

            <div class="w-full sm:w-auto">
                @if($prevLesson)
                    <a href="{{ route('student.lessons.show', [$module, $prevLesson]) }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 border border-border bg-white text-text-main font-medium hover:bg-gray-50 transition-colors">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="text-sm font-semibold">Sebelumnya</span>
                    </a>
                @else
                    <div class="w-full sm:w-auto"></div>
                @endif
            </div>

            <div class="text-xs text-text-muted font-medium">
                Pelajaran {{ $currentIndex + 1 }} dari {{ $lessons->count() }}
            </div>

            <div class="w-full sm:w-auto">
                @if($nextLesson)
                    @if($canProceed)
                        <a href="{{ route('student.lessons.show', [$module, $nextLesson]) }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-black text-white font-medium hover:bg-gray-800 transition-colors">
                            <span class="text-sm font-semibold">Selanjutnya</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    @else
                        <div class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-300 text-gray-500 font-medium cursor-not-allowed" title="Selesaikan kuis terlebih dahulu">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span class="text-sm font-semibold">Selesaikan Kuis</span>
                        </div>
                    @endif
                @else
                    <a href="{{ route('student.modules.show', $module) }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white font-medium hover:bg-green-700 transition-colors">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span class="text-sm font-semibold">Selesai</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* OPTIMAL QUILL CONTENT TYPOGRAPHY - Unified for Edit & View */
.lesson-content {
    max-width: 42rem;
    margin: 0 auto;
    font-size: 1.0625rem;
    line-height: 1.75;
    color: #1f2937;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    -webkit-font-smoothing: antialiased;
}

.lesson-content h1, .lesson-content h2, .lesson-content h3 {
    font-weight: 700;
    color: #111827;
    line-height: 1.3;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    letter-spacing: -0.025em;
}

.lesson-content h1 { font-size: 2.25rem; margin-top: 0; }
.lesson-content h2 { font-size: 1.875rem; }
.lesson-content h3 { font-size: 1.5rem; }

.lesson-content p { margin-bottom: 1.5rem; }
.lesson-content strong { font-weight: 700; color: #0f172a; }

.lesson-content a {
    color: #2563eb;
    text-decoration: underline;
    text-decoration-thickness: 1px;
    text-underline-offset: 2px;
    font-weight: 500;
}

.lesson-content a:hover { color: #1d4ed8; }

/* Lists - Proper Styling */
.lesson-content ul, .lesson-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.lesson-content ul {
    list-style-type: disc !important;
}

.lesson-content ol {
    list-style-type: decimal !important;
}

.lesson-content li {
    margin-bottom: 0.75rem;
    padding-left: 0.5rem;
    display: list-item !important;
}

.lesson-content li > ul,
.lesson-content li > ol {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

.lesson-content blockquote {
    border-left: 4px solid #3b82f6;
    padding: 1.25rem 1.5rem;
    margin: 2rem 0;
    color: #475569;
    font-style: italic;
    font-size: 1.125rem;
    background: #f8fafc;
    border-radius: 0 4px 4px 0;
}

.lesson-content code {
    background: #f1f5f9;
    color: #dc2626;
    padding: 0.2em 0.4em;
    border-radius: 3px;
    font-family: 'SF Mono', Monaco, Consolas, monospace;
    font-size: 0.9em;
    font-weight: 500;
}

.lesson-content pre {
    background: #1e293b;
    color: #f1f5f9;
    padding: 1.5rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 2rem 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.lesson-content pre code {
    background: none;
    padding: 0;
    color: #f1f5f9;
    font-size: 0.9375rem;
}

.lesson-content img {
    max-width: 100%;
    height: auto;
    margin: 2.5rem auto;
    display: block;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Tables - Full Support */
.lesson-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    border: 1px solid #e5e7eb;
}

.lesson-content th,
.lesson-content td {
    border: 1px solid #e5e7eb;
    padding: 0.75rem 1rem;
    text-align: left;
}

.lesson-content th {
    background: #f9fafb;
    font-weight: 600;
    color: #111827;
}

.lesson-content tr:nth-child(even) {
    background: #f9fafb;
}

.lesson-content tr:hover {
    background: #f3f4f6;
}
</style>
@endsection

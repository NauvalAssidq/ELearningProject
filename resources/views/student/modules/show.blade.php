@extends('layouts.app')

@section('title', $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header -->
    <div class="pb-8 border-b border-border">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Modul', 'url' => route('student.modules.index')],
        ['label' => \$module->title, 'truncate' => true],
    ]" class="mb-6" />
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-text-main mb-2">{{ $module->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-text-muted">
                    <span class="flex items-center gap-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        {{ $module->lessons_count }} Pelajaran
                    </span>
                    <span class="uppercase tracking-wide px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold">{{ $module->skill_level }}</span>
                </div>
            </div>

            @if($module->isCompletedBy(auth()->user()))
                <a href="{{ route('modules.certificate', $module) }}" target="_blank"
                   class="inline-flex items-center gap-2 bg-black text-white px-6 py-3 font-bold hover:bg-gray-800 transition-colors">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    Unduh Sertifikat
                </a>
            @endif
        </div>
        <p class="text-lg text-text-muted leading-relaxed mt-4">{{ $module->description }}</p>
    </div>

    <!-- Lessons Section -->
    <div>
        @php
            $user = auth()->user();
            $completedLessons = $module->lessons->filter(fn($l) => $l->isCompletedBy($user))->count();
            $totalLessons = $module->lessons->count();
            $progressPercent = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        @endphp
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-text-main">Daftar Pelajaran</h2>
                <p class="text-sm text-text-muted">{{ $completedLessons }} dari {{ $totalLessons }} pelajaran selesai</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-32 h-2 bg-gray-200 overflow-hidden">
                    <div class="h-full bg-black transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                </div>
                <span class="text-sm font-bold">{{ round($progressPercent) }}%</span>
            </div>
        </div>

        <div class="bg-white border border-border divide-y divide-border">
            @forelse($module->lessons as $lesson)
                @php
                    $progress = $lesson->getProgressFor($user);
                    $isCompleted = $progress && $progress->is_completed;
                    $hasQuiz = $lesson->quiz()->exists();
                    $quizScore = $progress?->quizAttempt?->score;
                @endphp
                <a href="{{ route('student.lessons.show', [$module, $lesson]) }}" 
                   class="group flex items-center gap-4 p-5 hover:bg-surface-off transition-colors">
                    
                    {{-- Status Badge --}}
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center font-bold text-sm transition-all
                        {{ $isCompleted 
                            ? 'bg-green-500 text-white border-green-500' 
                            : 'bg-surface border border-border text-text-muted group-hover:bg-black group-hover:text-white group-hover:border-black' }}">
                        @if($isCompleted)
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        @else
                            {{ $loop->iteration }}
                        @endif
                    </div>
                    
                    {{-- Lesson Info --}}
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-lg text-text-main group-hover:text-black transition-colors mb-1">{{ $lesson->title }}</h4>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-text-muted">
                            @if($lesson->is_preview)
                                <span class="bg-green-50 text-green-700 px-2 py-0.5 border border-green-200 font-semibold uppercase tracking-wide">
                                    ✓ Preview
                                </span>
                            @endif
                            <span class="flex items-center gap-1">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                {{ $lesson->duration ?? 0 }} Menit
                            </span>
                            
                            {{-- Quiz Status --}}
                            @if($hasQuiz)
                                @if($isCompleted && $quizScore !== null)
                                    <span class="flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 border border-green-200 font-semibold">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Kuis: {{ $quizScore }}%
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 border border-yellow-200 font-semibold">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                        Kuis
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    {{-- Completion Status & Arrow --}}
                    <div class="flex items-center gap-3">
                        @if($isCompleted)
                            <span class="text-xs font-bold text-green-600 uppercase tracking-wide hidden sm:block">Selesai</span>
                        @endif
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 text-text-muted group-hover:text-black group-hover:translate-x-1 transition-all">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center text-text-muted">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-3 opacity-50">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    <p>Belum ada pelajaran dalam modul ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Proyek Akhir Section --}}
    @php
        $userSubmission = $module->projectSubmissions()->where('user_id', auth()->id())->first();
    @endphp
    
    <div class="bg-white border-2 border-black p-6 md:p-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-black text-white flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <polyline points="16 13 12 17 8 13"></polyline>
                            <line x1="12" y1="17" x2="12" y2="11"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-text-main uppercase tracking-wide">Proyek Akhir</h3>
                        <p class="text-xs text-text-muted uppercase tracking-widest">Final Project</p>
                    </div>
                </div>
                
                <p class="text-text-muted mb-4">
                    Kumpulkan proyek akhir Anda untuk menyelesaikan modul ini. Format yang diterima: ZIP, RAR, PDF, dokumen, gambar, dan video.
                </p>
                
                @if($userSubmission)
                    <div class="bg-surface-off border border-border p-4">
                        <div class="flex items-start gap-3">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600 flex-shrink-0 mt-0.5">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-text-main">Sudah Dikumpulkan</p>
                                <p class="text-sm text-text-muted mt-1 truncate">{{ $userSubmission->original_filename }}</p>
                                <p class="text-xs text-text-muted">{{ $userSubmission->file_size_human }} • {{ $userSubmission->created_at->format('d M Y, H:i') }}</p>
                                
                                @if($userSubmission->grade !== null)
                                    <div class="mt-3 pt-3 border-t border-border flex items-center gap-4">
                                        <div>
                                            <p class="text-xs text-text-muted uppercase tracking-wider">Nilai</p>
                                            <p class="text-2xl font-bold {{ $userSubmission->grade >= 60 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $userSubmission->grade }}<span class="text-sm text-text-muted">/100</span>
                                            </p>
                                        </div>
                                        @if($userSubmission->feedback)
                                            <div class="flex-1 border-l border-border pl-4">
                                                <p class="text-xs text-text-muted uppercase tracking-wider">Feedback Dosen</p>
                                                <p class="text-sm text-text-main mt-1">{{ $userSubmission->feedback }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-2 flex items-center gap-1 text-yellow-600 text-xs">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        Menunggu penilaian dosen
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-text-muted text-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>Anda belum mengumpulkan proyek akhir</span>
                    </div>
                @endif
            </div>
            
            <div class="w-full md:w-auto">
                <a href="{{ route('student.projects.create', $module) }}" 
                   class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-black text-white px-6 py-4 font-bold uppercase tracking-wider text-sm hover:bg-gray-800 transition-colors">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="16 16 12 12 8 16"></polyline>
                        <line x1="12" y1="12" x2="12" y2="21"></line>
                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                    </svg>
                    {{ $userSubmission ? 'Perbarui Proyek' : 'Upload Proyek' }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


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
        ['label' => $module->title, 'truncate' => true],
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
            @elseif(!auth()->user()->isEnrolledIn($module))
                <form action="{{ route('student.modules.enroll', $module) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center gap-2 bg-black text-white px-8 py-3 hover:bg-gray-800 transition-all hover:scale-105 shadow-lg">
                        <span>Daftar Sekarang</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </form>
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
            @php
                $isEnrolled = auth()->user()->isEnrolledIn($module);
            @endphp

            @forelse($module->lessons as $lesson)
                @php
                    $progress = $isEnrolled ? $lesson->getProgressFor($user) : null;
                    $isCompleted = $progress && $progress->is_completed;
                    $hasQuiz = $lesson->quiz()->exists();
                    $quizScore = $progress?->quizAttempt?->score;
                @endphp

                @if($isEnrolled)
                    <a href="{{ route('student.lessons.show', [$module, $lesson]) }}" 
                       class="group flex items-center gap-4 p-5 hover:bg-surface-off transition-colors">
                @else
                    <div class="group flex items-center gap-4 p-5 bg-gray-50 opacity-75 cursor-not-allowed">
                @endif
                    
                    {{-- Status Badge --}}
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center font-bold text-sm transition-all
                        {{ $isCompleted 
                            ? 'bg-green-500 text-white border-green-500' 
                            : ($isEnrolled ? 'bg-surface border border-border text-text-muted group-hover:bg-black group-hover:text-white group-hover:border-black' : 'bg-gray-200 text-gray-400 border border-gray-300') }}">
                        @if($isCompleted)
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        @elseif(!$isEnrolled)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        @else
                            {{ $loop->iteration }}
                        @endif
                    </div>
                    
                    {{-- Lesson Info --}}
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-lg {{ $isEnrolled ? 'text-text-main group-hover:text-black' : 'text-gray-500' }} transition-colors mb-1">
                            {{ $lesson->title }}
                        </h4>
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
                            @if($hasQuiz && $isEnrolled)
                                @php
                                    // Fetch latest attempt manually if progress doesn't show it (e.g. failed attempt)
                                    $attempt = $lesson->quiz->attempts()->where('user_id', $user->id)->latest()->first();
                                    $score = $attempt ? $attempt->score : null;
                                    $passed = $score !== null && $score >= 60;
                                @endphp

                                @if($passed)
                                    <span class="flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 border border-green-200 font-semibold rounded">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Kuis: {{ $score }}%
                                    </span>
                                @elseif($score !== null)
                                    <span class="flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 border border-red-200 font-semibold rounded">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Gagal: {{ $score }}%
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 px-2 py-0.5 bg-yellow-100 text-yellow-700 border border-yellow-200 font-semibold rounded">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                        Belum Kuis
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    {{-- Completion Status & Arrow --}}
                    <div class="flex items-center gap-3">
                        @if($isEnrolled)
                            @if($isCompleted)
                                <span class="text-xs font-bold text-green-600 uppercase tracking-wide hidden sm:block">Selesai</span>
                            @endif
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 text-text-muted group-hover:text-black group-hover:translate-x-1 transition-all">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        @else
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wide flex items-center gap-1">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                Terkunci
                            </span>
                        @endif
                    </div>

                @if($isEnrolled)
                    </a>
                @else
                    </div>
                @endif
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
    
    {{-- Proyek Akhir Section - Distinct Action Card --}}
    @php
        $userSubmission = $module->projectSubmissions()->where('user_id', auth()->id())->first();
        $isUnlocked = $module->hasCompletedAllLessons(auth()->user());
    @endphp

    <div class="border border-border bg-white relative overflow-hidden">
        {{-- Header Status Bar --}}
        <div class="px-8 py-6 bg-surface-off border-b border-border flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-text-main uppercase tracking-tight">Proyek Akhir</h3>
                <p class="text-sm text-text-muted">Langkah Terakhir Menuju Sertifikasi</p>
            </div>
            
            <div class="flex items-center gap-3">
                @if($userSubmission)
                    @if($userSubmission->grade !== null)
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 font-bold uppercase tracking-wider text-xs">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Selesai & Dinilai
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-800 font-bold uppercase tracking-wider text-xs">
                            <div class="w-2 h-2 bg-yellow-600 rounded-full animate-pulse"></div>
                            Menunggu Penilaian
                        </span>
                    @endif
                @elseif($isUnlocked)
                     <span class="inline-flex items-center gap-2 px-4 py-2 bg-black text-white font-bold uppercase tracking-wider text-xs">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M2 12h20"></path>
                        </svg>
                        Siap Dikerjakan
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 font-bold uppercase tracking-wider text-xs">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Terkunci
                    </span>
                @endif
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            <div class="md:col-span-2 space-y-4">
                 @if($module->project_instruction)
                    <div class="prose max-w-none text-text-muted text-sm">
                        <p class="whitespace-pre-line">{{ Str::limit($module->project_instruction, 300) }}</p>
                    </div>
                    @if(strlen($module->project_instruction) > 300)
                        <p class="text-xs text-text-main font-bold italic">Lihat instruksi lengkap di halaman upload.</p>
                    @endif
                @else
                    <p class="text-text-muted">
                        Kumpulkan proyek akhir Anda untuk menyelesaikan modul ini. Pastikan file sesuai format yang ditentukan.
                    </p>
                @endif

                @if($userSubmission && $userSubmission->grade !== null)
                     <div class="bg-surface-off p-4 border border-border inline-block min-w-[200px]">
                        <p class="text-xs uppercase tracking-widest text-text-muted mb-1">Nilai Anda</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold {{ $userSubmission->grade >= 60 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $userSubmission->grade }}
                            </span>
                            <span class="text-sm text-text-muted font-bold">/ 100</span>
                        </div>
                     </div>
                @endif
            </div>

            <div class="md:col-span-1 flex flex-col items-end justify-center h-full">
                @if($isUnlocked)
                    <a href="{{ route('student.projects.create', $module) }}" 
                       class="w-full md:w-auto inline-flex items-center justify-center gap-3 bg-black text-white px-8 py-4 font-bold uppercase tracking-widest hover:bg-gray-800 transition-all hover:translate-y-[-2px] hover:shadow-lg group">
                        <span>{{ $userSubmission ? 'Lihat Detail / Update' : 'Mulai Proyek' }}</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="group-hover:translate-x-1 transition-transform">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                    @if($module->project_attachment)
                         <a href="{{ asset($module->project_attachment) }}" download class="mt-4 text-xs font-bold text-text-muted uppercase tracking-wider hover:text-black flex items-center gap-2">
                             <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download Attachments
                         </a>
                    @endif
                @else
                    <div class="bg-gray-100 p-4 border border-gray-200 w-full text-center">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Syarat Terkunci</p>
                        <p class="text-sm font-bold text-gray-600">Selesaikan Semua Pelajaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


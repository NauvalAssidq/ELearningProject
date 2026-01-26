@extends('layouts.app')

@section('title', 'Review Submission - ' . $submission->user->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
    {{-- Header --}}
    <div>
        <a href="{{ route('lecturer.submissions.index', $module) }}" class="text-sm text-text-muted hover:text-black inline-flex items-center gap-2 mb-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Kembali ke Submissions
        </a>
        <h1 class="text-3xl font-bold text-text-main">Review Submission</h1>
        <p class="text-text-muted">{{ $module->title }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Student Info --}}
            <div class="bg-white border border-border p-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-text-muted mb-4">Informasi Siswa</h2>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-text-main">{{ $submission->user->name }}</h3>
                        <p class="text-text-muted">{{ $submission->user->email }}</p>
                        @if($submission->user->skill_level)
                            @php
                                $levelColors = [
                                    'pemula' => 'bg-green-100 text-green-800',
                                    'menengah' => 'bg-yellow-100 text-yellow-800',
                                    'mahir' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-bold uppercase {{ $levelColors[$submission->user->skill_level] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($submission->user->skill_level) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- File Info --}}
            <div class="bg-white border border-border p-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-text-muted mb-4">File Submission</h2>
                
                <div class="flex items-start gap-4 p-4 bg-surface-off border border-border">
                    <div class="w-12 h-12 bg-blue-100 flex items-center justify-center rounded">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-text-main truncate">{{ $submission->original_filename }}</p>
                        <p class="text-sm text-text-muted">{{ $submission->file_size_human }}</p>
                        <p class="text-xs text-text-muted mt-1">
                            Submitted: {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : $submission->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <a href="{{ asset('storage/' . $submission->file_path) }}" download 
                       class="flex-shrink-0 inline-flex items-center gap-2 bg-black text-white px-4 py-2 font-bold text-sm hover:bg-gray-800 transition-colors">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>

        {{-- Grading Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white border border-border p-6 sticky top-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-text-muted mb-4">Penilaian</h2>
                
                <form action="{{ route('lecturer.submissions.grade', [$module, $submission]) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">Nilai (0-100)</label>
                        <input type="number" name="grade" min="0" max="100" required
                               value="{{ old('grade', $submission->grade) }}"
                               class="w-full border border-border p-3 text-2xl font-bold text-center focus:outline-none focus:border-black transition-colors"
                               placeholder="85">
                        @error('grade')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">Feedback (Opsional)</label>
                        <textarea name="feedback" rows="4"
                                  class="w-full border border-border p-3 focus:outline-none focus:border-black transition-colors resize-none"
                                  placeholder="Tuliskan feedback untuk siswa...">{{ old('feedback', $submission->feedback) }}</textarea>
                        @error('feedback')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-black text-white px-6 py-3 font-bold hover:bg-gray-800 transition-colors">
                        {{ $submission->grade !== null ? 'Update Nilai' : 'Simpan Nilai' }}
                    </button>
                </form>

                @if($submission->grade !== null)
                    <div class="mt-6 pt-6 border-t border-border">
                        <p class="text-xs text-text-muted uppercase tracking-wider mb-2">Nilai Saat Ini</p>
                        <div class="flex items-center justify-center">
                            <span class="text-5xl font-bold {{ $submission->grade >= 60 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $submission->grade }}
                            </span>
                            <span class="text-2xl text-text-muted ml-1">/100</span>
                        </div>
                        @if($submission->grade >= 60)
                            <p class="text-center text-green-600 font-bold mt-2">LULUS ✓</p>
                        @else
                            <p class="text-center text-red-600 font-bold mt-2">TIDAK LULUS</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

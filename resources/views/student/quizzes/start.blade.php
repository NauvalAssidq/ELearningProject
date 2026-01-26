@extends('layouts.app')

@section('title', 'Kuis - ' . $lesson->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => \$module->title, 'url' => route('student.modules.show', \$module), 'truncate' => true],
        ['label' => 'Kuis: ' . \$lesson->title, 'truncate' => true],
    ]" class="mb-6" />

    <div class="mb-8">
        <p class="text-sm text-text-muted mb-2">{{ $module->title }} / {{ $lesson->title }}</p>
        <h1 class="text-3xl font-bold text-text-main">{{ $quiz->title }}</h1>
        <div class="flex items-center gap-4 mt-2 text-sm text-text-muted">
            <span>{{ $questions->count() }} Pertanyaan</span>
            <span>•</span>
            <span>{{ $quiz->time_limit }} Menit</span>
        </div>
        @if($quiz->description)
            <p class="mt-4 text-text-muted">{{ $quiz->description }}</p>
        @endif
    </div>

    <form action="{{ route('student.quizzes.submit', [$module, $lesson]) }}" method="POST" id="quizForm">
        @csrf
        
        <div class="space-y-6">
            @foreach($questions as $index => $question)
                <div class="bg-white border border-border p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <span class="flex-shrink-0 w-8 h-8 bg-black text-white flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </span>
                        <p class="text-lg font-medium text-text-main">{{ $question->question_text }}</p>
                    </div>
                    
                    <div class="ml-12 space-y-3">
                        @foreach($question->options as $option)
                            <label class="flex items-center gap-3 p-3 border border-border hover:bg-surface-off cursor-pointer transition-colors">
                                <input type="radio" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $option->id }}"
                                       class="w-4 h-4 text-black focus:ring-black">
                                <span class="text-text-main">{{ $option->option_text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-between items-center">
            <a href="{{ route('student.lessons.show', [$module, $lesson]) }}" 
               class="text-sm font-bold text-text-muted hover:text-black">← Kembali ke Pelajaran</a>
            <button type="submit" 
                    class="inline-flex items-center justify-center gap-3 bg-black text-white px-8 py-4 font-bold text-lg hover:bg-gray-800 transition-all">
                Selesai & Lihat Hasil
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection

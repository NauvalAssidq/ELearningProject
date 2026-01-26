@extends('layouts.app')

@section('title', 'Tes Penempatan - Mulai')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-main mb-2">Tes Penempatan</h1>
        <p class="text-text-muted">Waktu: {{ $timeLimit }} menit | {{ $questions->count() }} pertanyaan</p>
    </div>

    <form action="{{ route('placement.submit') }}" method="POST" id="quizForm">
        @csrf
        
        <div class="space-y-6">
            @foreach($questions as $index => $question)
                <div class="bg-white border border-border p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <span class="flex-shrink-0 w-8 h-8 bg-black text-white flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="text-lg font-medium text-text-main">{{ $question->question_text }}</p>
                            @php
                                $difficultyColors = [
                                    'easy' => 'bg-green-100 text-green-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'hard' => 'bg-red-100 text-red-800',
                                ];
                                $difficultyLabels = ['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'];
                            @endphp
                            <span class="inline-block mt-2 px-2 py-0.5 text-xs font-bold uppercase {{ $difficultyColors[$question->difficulty] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $difficultyLabels[$question->difficulty] ?? $question->difficulty }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="ml-12 space-y-3">
                        @foreach($question->options->shuffle() as $option)
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

        <div class="mt-8 flex justify-end">
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

<script>
    // Simple timer (optional enhancement)
    let timeLimit = {{ $timeLimit }} * 60; // in seconds
    
    function updateTimer() {
        const minutes = Math.floor(timeLimit / 60);
        const seconds = timeLimit % 60;
        // You could display this in a fixed header if needed
        timeLimit--;
        
        if (timeLimit < 0) {
            document.getElementById('quizForm').submit();
        }
    }
    
    // Start timer
    setInterval(updateTimer, 1000);
</script>
@endsection

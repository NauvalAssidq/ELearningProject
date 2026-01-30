@extends('layouts.app')

@section('title', 'Asesmen Level Up - Kuis')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-black">Asesmen {{ ucfirst($user->skill_level) }} → {{ ucfirst($user->getNextLevel()) }}</h1>
            <p class="text-gray-600">Jawab 15 soal berikut dengan benar.</p>
        </div>
        <div class="text-right">
            <p class="text-xs uppercase tracking-widest text-gray-500">Passing Grade</p>
            <p class="text-2xl font-bold text-black">75%</p>
        </div>
    </div>

    <!-- Quiz Form -->
    <form action="{{ route('assessment.submit') }}" method="POST" id="assessmentForm">
        @csrf
        
        <div class="space-y-6">
            @foreach($questions as $index => $question)
                <div class="bg-white border border-gray-200 p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <span class="flex items-center justify-center w-8 h-8 bg-black text-white text-sm font-bold shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <p class="text-gray-800 font-medium leading-relaxed">{{ $question->question_text }}</p>
                    </div>
                    
                    <div class="space-y-2 ml-12">
                        @foreach($question->options as $option)
                            <label class="flex items-center gap-3 p-3 border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:bg-black has-[:checked]:text-white has-[:checked]:border-black">
                                <input type="radio" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $option->id }}" 
                                       class="w-4 h-4 accent-black"
                                       required>
                                <span>{{ $option->option_text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Submit -->
        <div class="mt-8 flex items-center justify-between">
            <p class="text-gray-500 text-sm">Pastikan Anda telah menjawab semua soal.</p>
            <button type="submit" 
                    class="bg-black text-white px-8 py-3 font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition-colors"
                    onclick="return confirm('Apakah Anda yakin ingin mengirim jawaban?')">
                Kirim Jawaban
            </button>
        </div>
    </form>

</div>
@endsection

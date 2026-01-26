@extends('layouts.app')

@section('title', 'Tambah Pertanyaan - Bank Soal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="questionBuilder()">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Bank Soal', 'url' => route('lecturer.bank-soal.index')],
        ['label' => 'Tambah Pertanyaan'],
    ]" class="mb-6" />

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-main">Tambah Pertanyaan</h1>
        <p class="text-text-muted mt-1">Pertanyaan akan ditambahkan ke bank soal untuk tes penempatan.</p>
    </div>

    <form action="{{ route('lecturer.bank-soal.store') }}" method="POST" class="space-y-8">
        @csrf
        
        {{-- Question Info --}}
        <div class="bg-white border border-border p-6 space-y-6">
            <h2 class="text-lg font-bold uppercase tracking-wide">Informasi Pertanyaan</h2>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2">Teks Pertanyaan</label>
                <textarea name="question_text" rows="3" required
                    class="w-full border border-border p-3 focus:outline-none focus:border-black"
                    placeholder="Tulis pertanyaan di sini...">{{ old('question_text') }}</textarea>
                @error('question_text') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2">Tingkat Kesulitan</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="difficulty" value="easy" {{ old('difficulty', 'medium') === 'easy' ? 'checked' : '' }}
                            class="w-4 h-4 text-green-600 focus:ring-green-500">
                        <span class="text-sm font-medium">Mudah</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="difficulty" value="medium" {{ old('difficulty', 'medium') === 'medium' ? 'checked' : '' }}
                            class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                        <span class="text-sm font-medium">Sedang</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="difficulty" value="hard" {{ old('difficulty', 'medium') === 'hard' ? 'checked' : '' }}
                            class="w-4 h-4 text-red-600 focus:ring-red-500">
                        <span class="text-sm font-medium">Sulit</span>
                    </label>
                </div>
                @error('difficulty') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Options --}}
        <div class="bg-white border border-border p-6 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold uppercase tracking-wide">Pilihan Jawaban</h2>
                    <p class="text-xs text-text-muted">Klik radio button untuk menandai jawaban yang benar.</p>
                </div>
                <button type="button" @click="addOption()" 
                    class="text-sm font-bold text-accent hover:underline" x-show="options.length < 6">
                    + Tambah Pilihan
                </button>
            </div>

            <template x-for="(option, index) in options" :key="index">
                <div class="flex items-center gap-3">
                    <input type="radio" name="correct" :value="index" x-model="correct"
                        class="w-5 h-5 text-green-600 focus:ring-green-500">
                    <input type="text" :name="'options[' + index + '][text]'" x-model="option.text" required
                        class="flex-1 border border-border p-3 focus:outline-none focus:border-black"
                        placeholder="Tulis pilihan jawaban...">
                    <button type="button" @click="removeOption(index)"
                        class="p-2 text-red-600 hover:bg-red-50 transition-colors" x-show="options.length > 2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </template>

            @error('options') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            @error('correct') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('lecturer.bank-soal.index') }}" 
               class="px-6 py-3 text-sm font-bold text-text-muted hover:text-black">Batal</a>
            <button type="submit" class="bg-black text-white px-8 py-3 font-bold hover:bg-gray-800 transition-colors">
                Simpan Pertanyaan
            </button>
        </div>
    </form>
</div>

<script>
function questionBuilder() {
    return {
        options: [
            { text: '' },
            { text: '' },
            { text: '' },
            { text: '' }
        ],
        correct: 0,
        addOption() {
            if (this.options.length < 6) {
                this.options.push({ text: '' });
            }
        },
        removeOption(index) {
            if (this.options.length > 2) {
                this.options.splice(index, 1);
                if (this.correct >= index && this.correct > 0) {
                    this.correct--;
                }
            }
        }
    }
}
</script>
@endsection

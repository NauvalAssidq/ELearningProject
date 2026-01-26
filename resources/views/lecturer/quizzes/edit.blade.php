@extends('layouts.app')

@section('title', 'Edit Kuis - ' . $quiz->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="quizBuilder()">
    <div class="mb-8">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Dasbor', 'url' => route('dashboard')],
            ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
            ['label' => $module->title, 'url' => route('lecturer.modules.show', $module), 'truncate' => true],
            ['label' => 'Edit Kuis'],
        ]" class="mb-4" />
        <h1 class="text-3xl font-bold text-text-main">Edit Kuis</h1>
        <p class="text-text-muted mt-1">{{ $lesson->title }} • {{ $module->title }}</p>
    </div>

    <form action="{{ route('lecturer.quizzes.update', [$module, $quiz]) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        {{-- Basic Info --}}
        <div class="bg-white border border-border p-6 space-y-4">
            <h2 class="text-lg font-bold uppercase tracking-wide">Informasi Kuis</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2">Pelajaran</label>
                    <input type="text" disabled value="{{ $lesson->title }}"
                        class="w-full border border-border p-3 bg-surface-off text-text-muted cursor-not-allowed">
                    <p class="text-xs text-text-muted mt-1">Pelajaran tidak dapat diubah.</p>
                </div>
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2">Batas Waktu (Menit)</label>
                    <input type="number" name="time_limit" value="{{ $quiz->time_limit }}" min="1" max="180" required
                        class="w-full border border-border p-3 focus:outline-none focus:border-black">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2">Judul Kuis</label>
                <input type="text" name="title" required value="{{ $quiz->title }}"
                    class="w-full border border-border p-3 focus:outline-none focus:border-black">
            </div>
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" rows="2"
                    class="w-full border border-border p-3 focus:outline-none focus:border-black">{{ $quiz->description }}</textarea>
            </div>
        </div>

        {{-- Questions --}}
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold uppercase tracking-wide">Pertanyaan</h2>
                <button type="button" @click="addQuestion()" 
                    class="text-sm font-bold text-accent hover:underline">+ Tambah Pertanyaan</button>
            </div>

            <template x-for="(question, qIndex) in questions" :key="qIndex">
                <div class="bg-white border border-border p-6 space-y-4">
                    <div class="flex justify-between items-start">
                        <span class="w-8 h-8 bg-black text-white flex items-center justify-center font-bold text-sm"
                            x-text="qIndex + 1"></span>
                        <button type="button" @click="removeQuestion(qIndex)" 
                            class="text-red-600 hover:text-red-800 text-xs font-bold" x-show="questions.length > 1">
                            Hapus
                        </button>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2">Teks Pertanyaan</label>
                        <textarea :name="'questions[' + qIndex + '][text]'" rows="2" required
                            x-model="question.text"
                            class="w-full border border-border p-3 focus:outline-none focus:border-black"
                            placeholder="Tulis pertanyaan..."></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider">Pilihan Jawaban</label>
                        <p class="text-xs text-text-muted">Klik radio button untuk menandai jawaban yang benar.</p>
                        
                        <template x-for="(option, oIndex) in question.options" :key="oIndex">
                            <div class="flex items-center gap-3">
                                <input type="radio" :name="'questions[' + qIndex + '][correct]'" :value="oIndex"
                                    x-model="question.correct"
                                    class="w-4 h-4 text-green-600 focus:ring-green-500">
                                <input type="text" :name="'questions[' + qIndex + '][options][' + oIndex + '][text]'"
                                    x-model="option.text" required
                                    class="flex-1 border border-border p-2 focus:outline-none focus:border-black"
                                    placeholder="Tulis pilihan...">
                                <button type="button" @click="removeOption(qIndex, oIndex)"
                                    class="text-red-600 hover:text-red-800 p-1" x-show="question.options.length > 2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        
                        <button type="button" @click="addOption(qIndex)"
                            class="text-xs font-bold text-text-muted hover:text-black mt-2" x-show="question.options.length < 6">
                            + Tambah Pilihan
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Submit --}}
        <div class="flex justify-between items-center">
            {{-- Delete button placeholder - actual form is outside --}}
            <div id="delete-placeholder"></div>
            
            <div class="flex gap-4">
                <a href="{{ route('lecturer.modules.show', $module) }}" 
                    class="px-6 py-3 text-sm font-bold text-text-muted hover:text-black">Batal</a>
                <button type="submit" class="bg-black text-white px-8 py-3 font-bold hover:bg-gray-800 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
    
    {{-- Delete Form - MUST be outside main form to prevent method conflict --}}
    <form action="{{ route('lecturer.quizzes.destroy', [$module, $quiz]) }}" method="POST" class="mt-4"
          onsubmit="return confirm('Yakin ingin menghapus kuis ini? Semua data jawaban siswa akan hilang.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-sm">
            Hapus Kuis
        </button>
    </form>
</div>

<script>
function quizBuilder() {
    return {
        questions: @json($quiz->questions->map(fn($q) => [
            'text' => $q->question_text,
            'options' => $q->options->map(fn($o) => ['text' => $o->option_text])->toArray(),
            'correct' => $q->options->search(fn($o) => $o->is_correct) !== false 
                ? $q->options->search(fn($o) => $o->is_correct) 
                : 0
        ])->toArray()),
        addQuestion() {
            this.questions.push({
                text: '',
                options: [{ text: '' }, { text: '' }, { text: '' }, { text: '' }],
                correct: 0
            });
        },
        removeQuestion(index) {
            if (this.questions.length > 1) {
                this.questions.splice(index, 1);
            }
        },
        addOption(qIndex) {
            if (this.questions[qIndex].options.length < 6) {
                this.questions[qIndex].options.push({ text: '' });
            }
        },
        removeOption(qIndex, oIndex) {
            if (this.questions[qIndex].options.length > 2) {
                this.questions[qIndex].options.splice(oIndex, 1);
                if (this.questions[qIndex].correct >= oIndex) {
                    this.questions[qIndex].correct = Math.max(0, this.questions[qIndex].correct - 1);
                }
            }
        }
    }
}
</script>
@endsection

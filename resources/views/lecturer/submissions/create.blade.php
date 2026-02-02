@extends('layouts.app')

@section('title', 'Manual Upload - ' . $module->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
        ['label' => $module->title, 'url' => route('lecturer.modules.show', $module), 'truncate' => true],
        ['label' => 'Submissions', 'url' => route('lecturer.submissions.index', $module)],
        ['label' => 'Manual Upload'],
    ]" class="mb-6" />

    <div class="space-y-1">
        <h1 class="text-3xl font-bold text-text-main">Manual Upload Proyek</h1>
        <p class="text-text-muted">Upload proyek atas nama siswa jika terjadi kendala teknis.</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('lecturer.submissions.store', $module) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-border p-8 shadow-sm">
        @csrf

        <div class="space-y-6">
            {{-- Student Selector --}}
            <div>
                <label for="user_id" class="block text-sm font-bold text-text-main mb-2">Pilih Siswa</label>
                <select name="user_id" id="user_id" class="w-full bg-surface-off border-border focus:border-black focus:ring-0 p-3 outline-none transition-colors" required>
                    <option value="" disabled selected>-- Pilih Siswa --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-text-muted mt-1">Hanya siswa yang terdaftar dalam modul ini.</p>
            </div>

            {{-- File Upload --}}
            <div>
                <label for="project_file" class="block text-sm font-bold text-text-main mb-2">File Proyek</label>
                <input type="file" name="project_file" id="project_file" class="block w-full text-sm text-text-muted
                    file:mr-4 file:py-2 file:px-4
                    file:border-0 file:text-sm file:font-bold
                    file:bg-black file:text-white
                    hover:file:bg-gray-800 transition-colors" required>
                <p class="text-xs text-text-muted mt-1">Format: ZIP, RAR, PDF, Dokumen. Max: 50MB.</p>
            </div>

            <div class="border-t border-border pt-6 mt-6">
                <h3 class="font-bold text-lg mb-4">Penilaian (Opsional)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="grade" class="block text-sm font-bold text-text-main mb-2">Nilai (0-100)</label>
                        <input type="number" name="grade" id="grade" min="0" max="100" value="{{ old('grade') }}" 
                               class="w-full bg-surface-off border-border focus:border-black focus:ring-0 p-3 outline-none transition-colors">
                    </div>
                    
                    <div>
                        <label for="feedback" class="block text-sm font-bold text-text-main mb-2">Feedback</label>
                        <textarea name="feedback" id="feedback" rows="3" 
                                  class="w-full bg-surface-off border-border focus:border-black focus:ring-0 p-3 outline-none transition-colors">{{ old('feedback') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-black text-white px-8 py-3 font-bold uppercase tracking-wider hover:bg-gray-800 transition-colors">
                    Upload & Simpan
                </button>
                <a href="{{ route('lecturer.submissions.index', $module) }}" class="text-text-muted font-bold hover:text-black transition-colors">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

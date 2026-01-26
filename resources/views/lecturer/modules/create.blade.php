@extends('layouts.app')

@section('title', 'Buat Modul Baru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Manajemen Modul', 'url' => route('lecturer.modules.index')],
        ['label' => 'Buat Baru'],
    ]" />

    <div>
        <h1 class="text-3xl font-bold tracking-tight text-text-main">BUAT MODUL BARU</h1>
        <p class="text-text-muted mt-2">Mulai buat modul pembelajaran baru Anda.</p>
    </div>

    <div class="bg-white border border-border p-8">
        <form action="{{ route('lecturer.modules.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Judul Modul</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors placeholder-gray-400"
                    placeholder="Contoh: Pemrograman Web Dasar">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Deskripsi Singkat</label>
                <textarea name="description" rows="4" required
                    class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors placeholder-gray-400"
                    placeholder="Jelaskan apa yang akan dipelajari dalam modul ini...">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Tingkat Kesulitan</label>
                    <div class="relative">
                        <select name="skill_level" required
                            class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors appearance-none">
                            <option value="">Pilih Level</option>
                            <option value="pemula" {{ old('skill_level') == 'pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="menengah" {{ old('skill_level') == 'menengah' ? 'selected' : '' }}>Menengah</option>
                            <option value="mahir" {{ old('skill_level') == 'mahir' ? 'selected' : '' }}>Mahir</option>
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                    @error('skill_level') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Gambar Sampul</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:bg-surface-off file:text-text-main hover:file:bg-black hover:file:text-white transition-colors">
                    <p class="text-xs text-text-muted mt-1">Format: JPG, PNG. Maks: 2MB.</p>
                    @error('image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-border flex justify-end gap-4">
                <a href="{{ route('lecturer.modules.index') }}" class="px-6 py-3 text-sm font-bold uppercase tracking-wider text-text-muted hover:text-black">Batal</a>
                <button type="submit" class="bg-black text-white px-8 py-3 font-bold uppercase tracking-wider text-sm hover:bg-accent transition-colors">
                    Buat Modul
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

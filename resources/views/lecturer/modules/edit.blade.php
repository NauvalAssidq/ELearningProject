@extends('layouts.app')

@section('title', 'Edit Modul - ' . $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
        ['label' => $module->title, 'url' => route('lecturer.modules.show', $module), 'truncate' => true],
        ['label' => 'Edit'],
    ]" />

    <div>
        <h1 class="text-3xl font-bold tracking-tight text-text-main">EDIT MODUL</h1>
        <p class="text-text-muted mt-2">Perbarui informasi modul pembelajaran Anda.</p>
    </div>

    <div class="bg-white border border-border p-8">
        <form action="{{ route('lecturer.modules.update', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Judul Modul</label>
                <input type="text" name="title" value="{{ old('title', $module->title) }}" required
                    class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors placeholder-gray-400">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Deskripsi Singkat</label>
                <textarea name="description" rows="4" required
                    class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors placeholder-gray-400">{{ old('description', $module->description) }}</textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Tingkat Kesulitan</label>
                    <div class="relative">
                        <select name="skill_level" required
                            class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors appearance-none">
                            <option value="pemula" {{ old('skill_level', $module->skill_level) == 'pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="menengah" {{ old('skill_level', $module->skill_level) == 'menengah' ? 'selected' : '' }}>Menengah</option>
                            <option value="mahir" {{ old('skill_level', $module->skill_level) == 'mahir' ? 'selected' : '' }}>Mahir</option>
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
                    <div class="flex items-center gap-4">
                        @if($module->image)
                            <div class="w-16 h-16 bg-gray-100 flex-shrink-0">
                                <img src="{{ asset($module->image) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="image" accept="image/*"
                                class="w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:bg-surface-off file:text-text-main hover:file:bg-black hover:file:text-white transition-colors">
                            <p class="text-xs text-text-muted mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
                        </div>
                    </div>
                    @error('image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-border flex justify-end gap-4">
                <a href="{{ route('lecturer.modules.show', $module) }}" class="px-6 py-3 text-sm font-bold uppercase tracking-wider text-text-muted hover:text-black">Batal</a>
                <button type="submit" class="bg-black text-white px-8 py-3 font-bold uppercase tracking-wider text-sm hover:bg-accent transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

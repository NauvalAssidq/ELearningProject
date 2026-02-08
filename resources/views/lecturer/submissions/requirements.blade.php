@extends('layouts.app')

@section('title', 'Persyaratan Proyek - ' . $module->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
        ['label' => $module->title, 'url' => route('lecturer.modules.show', $module), 'truncate' => true],
        ['label' => 'Submissions', 'url' => route('lecturer.submissions.index', $module)],
        ['label' => 'Persyaratan'],
    ]" />

    <div class="flex items-end justify-between border-b border-black pb-6">
        <div>
            <h1 class="text-3xl font-bold text-text-main tracking-tight uppercase">Persyaratan Proyek</h1>
            <p class="text-text-muted mt-2">Atur instruksi dan lampiran untuk proyek akhir modul ini.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800 uppercase tracking-wide">Terdapat Kesalahan</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('lecturer.submissions.requirements.update', $module) }}" method="POST" enctype="multipart/form-data" 
          class="bg-white border border-border">
        @csrf
        @method('PUT')

        <div class="p-8 space-y-8">
            {{-- Instruction Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-black text-white flex items-center justify-center font-bold text-sm">1</div>
                    <label class="text-lg font-bold text-text-main uppercase tracking-wide">Instruksi Pengerjaan</label>
                </div>
                
                <div class="pl-11">
                    <textarea name="project_instruction" rows="8"
                        class="w-full bg-surface-off border border-border focus:border-black focus:ring-0 p-4 outline-none transition-all placeholder-gray-400 font-mono text-sm"
                        placeholder="Tuliskan instruksi detail di sini...">{{ old('project_instruction', $module->project_instruction) }}</textarea>
                    <p class="text-xs text-text-muted mt-2 flex items-center gap-1">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        Instruksi ini akan muncul di halaman proyek siswa. Markdown didukung secara terbatas.
                    </p>
                </div>
            </div>

            <div class="w-full h-px bg-border"></div>

            {{-- Attachment Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-black text-white flex items-center justify-center font-bold text-sm">2</div>
                    <label class="text-lg font-bold text-text-main uppercase tracking-wide">Lampiran / Resource</label>
                </div>

                <div class="pl-11 space-y-4">
                    @if($module->project_attachment)
                        <div x-data="{ markForDeletion: false }" 
                             class="flex items-center justify-between bg-surface-off p-4 border border-border group transition-all"
                             :class="markForDeletion ? 'border-red-500 bg-red-50' : 'hover:border-black'">
                            <div class="flex items-center gap-4 overflow-hidden transition-opacity" :class="markForDeletion ? 'opacity-50' : ''">
                                <div class="bg-white border border-border p-3 transition-colors" :class="markForDeletion ? 'border-red-200' : ''">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                         :class="markForDeletion ? 'text-red-400' : ''">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-text-muted uppercase tracking-wider mb-0.5" x-text="markForDeletion ? 'Akan Dihapus' : 'File Saat Ini'">File Saat Ini</p>
                                    <a href="{{ asset($module->project_attachment) }}" target="_blank" 
                                       class="font-bold text-text-main truncate hover:underline block transition-all"
                                       :class="markForDeletion ? 'line-through text-red-600' : ''">
                                        {{ basename($module->project_attachment) }}
                                    </a>
                                </div>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer px-3 py-1.5 border transition-all select-none"
                                   :class="markForDeletion ? 'bg-red-100 border-red-500 text-red-700 hover:bg-red-200' : 'bg-white border-border hover:border-red-500 hover:text-red-600'">
                                <input type="checkbox" name="remove_project_attachment" value="1" class="hidden" x-model="markForDeletion">
                                <span class="text-xs font-bold uppercase" x-text="markForDeletion ? 'Batal Hapus' : 'Hapus File'">Hapus File</span>
                                <svg x-show="!markForDeletion" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                <svg x-show="markForDeletion" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                    <path d="M3 3v5h5"></path>
                                </svg>
                            </label>
                        </div>
                    @endif

                    <div x-data="{ fileName: '' }" class="relative">
                        <label class="block w-full border-2 border-dashed border-border hover:border-black transition-all p-8 text-center cursor-pointer bg-surface-off hover:bg-white group">
                            <input type="file" name="project_attachment" class="hidden" @change="fileName = $event.target.files[0].name">
                            
                            <div class="space-y-2" x-show="!fileName">
                                <div class="w-12 h-12 bg-white border border-border mx-auto flex items-center justify-center group-hover:border-black transition-colors">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-text-muted group-hover:text-black">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-text-main uppercase tracking-wide group-hover:underline">Upload Lampiran Baru</p>
                                <p class="text-xs text-text-muted">Klik untuk memilih file (PDF, ZIP, DOCX - Max 50MB)</p>
                            </div>

                            <div class="flex items-center justify-center gap-3" x-show="fileName" x-cloak>
                                <div class="w-10 h-10 bg-black text-white flex items-center justify-center">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                        <polyline points="13 2 13 9 20 9"></polyline>
                                    </svg>
                                </div>
                                <span class="font-bold text-text-main" x-text="fileName"></span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-surface-off px-8 py-6 border-t border-border flex items-center justify-between">
            <a href="{{ route('lecturer.submissions.index', $module) }}" class="text-sm font-bold text-text-muted hover:text-black uppercase tracking-wide transition-colors">
                &larr; Kembali
            </a>
            <button type="submit" class="bg-black text-white px-8 py-3 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors flex items-center gap-2">
                <span>Simpan Perubahan</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection

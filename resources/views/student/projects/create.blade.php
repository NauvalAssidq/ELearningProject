@extends('layouts.app')

@section('title', 'Upload Proyek - ' . $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => \$module->title, 'url' => route('student.modules.show', \$module), 'truncate' => true],
        ['label' => 'Upload Proyek'],
    ]" class="mb-6" />

    {{-- Main Card --}}
    <div class="bg-white border border-border">
        {{-- Header --}}
        <div class="p-6 md:p-8 border-b border-border">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-black text-white flex items-center justify-center">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <polyline points="16 13 12 17 8 13"></polyline>
                        <line x1="12" y1="17" x2="12" y2="11"></line>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-text-main uppercase tracking-wide">Upload Proyek Akhir</h1>
                    <p class="text-sm text-text-muted">{{ $module->title }}</p>
                </div>
            </div>
        </div>

        {{-- Existing Submission Info --}}
        @if($existingSubmission)
            <div class="p-4 md:p-6 bg-surface-off border-b border-border">
                <div class="flex items-start gap-3">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-yellow-600 mt-0.5 flex-shrink-0">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-text-main">Proyek Sebelumnya</p>
                        <p class="text-sm text-text-muted mt-1 truncate">
                            {{ $existingSubmission->original_filename }} ({{ $existingSubmission->file_size_human }})
                        </p>
                        <p class="text-xs text-text-muted">
                            Dikumpulkan: {{ $existingSubmission->created_at->format('d M Y, H:i') }}
                        </p>
                        
                        @if($existingSubmission->grade !== null)
                            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 {{ $existingSubmission->grade >= 60 ? 'bg-green-100 border-green-200' : 'bg-red-100 border-red-200' }} border">
                                <span class="text-sm font-bold {{ $existingSubmission->grade >= 60 ? 'text-green-700' : 'text-red-700' }}">
                                    Nilai: {{ $existingSubmission->grade }}/100
                                </span>
                            </div>
                        @endif
                        
                        <p class="text-xs text-yellow-700 mt-2">
                            ⚠️ Upload file baru akan mengganti submission sebelumnya.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Upload Form --}}
        <form action="{{ route('student.projects.store', $module) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-6 md:p-8 space-y-6">
                {{-- File Upload Area --}}
                <div x-data="{
                    fileName: '',
                    fileSize: '',
                    dragover: false,
                    handleFile(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.fileName = file.name;
                            this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                        }
                    }
                }">
                    <label class="block text-xs font-bold uppercase tracking-widest text-text-main mb-3">File Proyek</label>
                    
                    <div class="relative border-2 border-dashed transition-all duration-200 p-10 text-center cursor-pointer"
                         :class="dragover ? 'border-black bg-gray-50' : (fileName ? 'border-black bg-surface-off' : 'border-border hover:border-black')"
                         @dragover.prevent="dragover = true"
                         @dragleave.prevent="dragover = false"
                         @drop.prevent="dragover = false; handleFile({target: {files: $event.dataTransfer.files}})">
                        
                        <input type="file" name="project_file" required 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               @change="handleFile($event)">
                        
                        <template x-if="!fileName">
                            <div>
                                <div class="w-16 h-16 bg-surface-off border border-border mx-auto mb-4 flex items-center justify-center">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-text-muted">
                                        <polyline points="16 16 12 12 8 16"></polyline>
                                        <line x1="12" y1="12" x2="12" y2="21"></line>
                                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                                    </svg>
                                </div>
                                <p class="font-bold text-text-main mb-1">Klik atau seret file ke sini</p>
                                <p class="text-sm text-text-muted">Maksimal 50MB</p>
                            </div>
                        </template>
                        
                        <template x-if="fileName">
                            <div class="flex items-center justify-center gap-4">
                                <div class="w-12 h-12 bg-black text-white flex items-center justify-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-text-main" x-text="fileName"></p>
                                    <p class="text-sm text-text-muted" x-text="fileSize"></p>
                                </div>
                                <div class="w-8 h-8 bg-green-100 text-green-600 flex items-center justify-center">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </template>
                    </div>

                    @error('project_file')
                        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Format Info --}}
                <div class="bg-surface-off p-4 border border-border">
                    <p class="text-xs font-bold uppercase tracking-widest text-text-main mb-2">Format yang Diterima</p>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach(['ZIP', 'RAR', 'PDF', 'DOCX', 'PNG', 'JPG', 'MP4'] as $format)
                            <span class="px-2 py-1 bg-white border border-border text-xs font-medium text-text-muted">{{ $format }}</span>
                        @endforeach
                    </div>
                    <p class="text-xs text-red-600">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="inline mr-1">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        Tidak boleh: .exe, .bat, .sh, .php, dan file executable lainnya.
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-6 md:p-8 border-t border-border bg-surface-off flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('student.modules.show', $module) }}" 
                   class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider text-text-muted hover:text-black border border-border bg-white">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center gap-2 bg-black text-white px-8 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-800 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="16 16 12 12 8 16"></polyline>
                        <line x1="12" y1="12" x2="12" y2="21"></line>
                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                    </svg>
                    {{ $existingSubmission ? 'Perbarui Proyek' : 'Upload Proyek' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

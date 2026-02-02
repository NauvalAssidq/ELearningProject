@extends('layouts.app')

@section('title', 'Upload Proyek - ' . $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => $module->title, 'url' => route('student.modules.show', $module), 'truncate' => true],
        ['label' => 'Upload Proyek'],
    ]" class="mb-8" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- LEFT COLUMN: Context & Instructions --}}
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h1 class="text-3xl font-bold text-text-main uppercase tracking-tight mb-2">Upload Proyek Akhir</h1>
                <p class="text-lg text-text-muted">{{ $module->title }}</p>
            </div>

            {{-- Instructions Card --}}
            <div class="bg-white border border-border">
                <div class="bg-surface-off px-6 py-4 border-b border-border flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-text-main">Instruksi Pengerjaan</h2>
                    @if($module->project_attachment)
                        <a href="{{ asset($module->project_attachment) }}" download 
                           class="inline-flex items-center gap-2 text-xs font-bold uppercase hover:text-black hover:underline transition-colors text-text-muted">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download Lampiran
                        </a>
                    @endif
                </div>
                <div class="p-6 md:p-8 prose max-w-none text-text-main text-sm leading-relaxed">
                    @if($module->project_instruction)
                        <p class="whitespace-pre-line">{{ $module->project_instruction }}</p>
                    @else
                        <p class="italic text-text-muted">Tidak ada instruksi khusus dari dosen. Silakan kumpulkan file proyek akhir Anda sesuai format yang ditentukan.</p>
                    @endif
                </div>
            </div>

            {{-- History / Previous Submission --}}
            @if($existingSubmission)
                <div class="bg-white border border-border">
                    <div class="bg-surface-off px-6 py-4 border-b border-border">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-text-main">Status Terkini</h2>
                    </div>
                    <div class="p-6 flex items-start gap-6">
                        <div class="w-16 h-16 bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-500">
                             <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap justify-between gap-4 mb-2">
                                <div>
                                    <h3 class="font-bold text-text-main text-lg">{{ $existingSubmission->original_filename }}</h3>
                                    <p class="text-xs text-text-muted uppercase tracking-wide mt-1">
                                        {{ $existingSubmission->file_size_human }} • {{ $existingSubmission->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                @if($existingSubmission->grade !== null)
                                    <div class="text-right">
                                        <p class="text-3xl font-bold {{ $existingSubmission->grade >= 60 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $existingSubmission->grade }}
                                        </p>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-text-muted">Nilai Akhir</p>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold uppercase tracking-wide">
                                        <div class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></div>
                                        Menunggu Penilaian
                                    </span>
                                @endif
                            </div>

                            @if($existingSubmission->feedback)
                                <div class="mt-4 p-4 bg-gray-50 border-l-4 border-black">
                                    <p class="text-xs font-bold text-text-muted uppercase tracking-wider mb-1">Feedback Dosen:</p>
                                    <p class="text-sm text-text-main">{{ $existingSubmission->feedback }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: Upload Action --}}
        <div>
            <div class="sticky top-6">
                <form action="{{ route('student.projects.store', $module) }}" method="POST" enctype="multipart/form-data" 
                      class="bg-black text-white p-6 shadow-xl relative overflow-hidden group">
                    
                    {{-- Decorative Circle --}}
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all"></div>

                    @csrf
                    
                    <h3 class="text-xl font-bold uppercase tracking-wide mb-6 relative z-10">Upload File</h3>

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
                    }" class="relative z-10">
                        
                        <div class="relative border-2 border-dashed border-white/20 hover:border-white transition-all duration-300 bg-white/5 p-8 text-center cursor-pointer group/drop"
                             :class="dragover ? 'bg-white/10 border-white' : ''"
                             @dragover.prevent="dragover = true"
                             @dragleave.prevent="dragover = false"
                             @drop.prevent="dragover = false; handleFile({target: {files: $event.dataTransfer.files}})">
                            
                            <input type="file" name="project_file" required 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   @change="handleFile($event)">
                            
                            <div class="space-y-4" x-show="!fileName">
                                <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto group-hover/drop:scale-110 transition-transform">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <polyline points="16 16 12 12 8 16"></polyline>
                                        <line x1="12" y1="12" x2="12" y2="21"></line>
                                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold uppercase tracking-wider text-sm">Drag & Drop</p>
                                    <p class="text-xs text-gray-400 mt-1">atau klik untuk browsing file</p>
                                </div>
                            </div>

                            <div x-show="fileName" x-cloak class="text-left">
                                <div class="flex items-center gap-3 bg-white text-black p-3 rounded-sm shadow-lg transform transition-all">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-sm truncate" x-text="fileName"></p>
                                        <p class="text-xs text-gray-600" x-text="fileSize"></p>
                                    </div>
                                    <div class="text-green-600">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-center text-xs text-white/50 mt-3">Klik lagi untuk mengganti file</p>
                            </div>
                        </div>

                        @error('project_file')
                            <p class="text-xs text-red-400 mt-2 font-bold bg-white/10 p-2">{{ $message }}</p>
                        @enderror

                        <div class="mt-8 space-y-4">
                            <button type="submit" class="w-full bg-white text-black font-bold uppercase tracking-widest py-4 hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                                <span>{{ $existingSubmission ? 'Update Proyek' : 'Kirim Proyek' }}</span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
                            
                            <a href="{{ route('student.modules.show', $module) }}" class="block text-center text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-white transition-colors">
                                Batalkan
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-white/10">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">Format yang Diizinkan</p>
                        <div class="flex flex-wrap gap-2 text-[10px] font-mono text-gray-400">
                            <span>ZIP</span> <span>PDF</span> <span>DOCX</span> <span>JPG</span> <span>MP4</span>
                        </div>
                        <p class="text-[10px] text-red-400 mt-2 opacity-70">
                            Maksimal 50MB. File .exe / executable dilarang.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

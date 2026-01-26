@extends('layouts.app')

@section('title', 'Edit Pelajaran: ' . $lesson->title)

@push('editor-styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@include('lecturer.lessons.css')
@endpush

@push('editor-scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div>
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Dasbor', 'url' => route('dashboard')],
            ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
            ['label' => $module->title, 'url' => route('lecturer.modules.show', $module), 'truncate' => true],
            ['label' => $lesson->title, 'truncate' => true],
        ]" class="mb-4" />
        <h1 class="text-3xl font-bold text-text-main">Edit Pelajaran</h1>
        <p class="text-text-muted mt-1">{{ $lesson->title }}</p>
    </div>

    <div class="bg-white border border-border p-8">
        <form action="{{ route('lecturer.lessons.update', [$module, $lesson]) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Judul Pelajaran</label>
                <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required
                    class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors"
                    placeholder="Contoh: Pengenalan Dasar HTML">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-4" x-data="{ 
                preview: '{{ $lesson->media_url ? asset($lesson->media_url) : '' }}',
                previewType: '{{ $lesson->media_url ? (\Illuminate\Support\Str::endsWith(strtolower($lesson->media_url), ['mp4','mov','webm','ogg']) ? 'video' : 'image') : null }}',
                fileName: '{{ $lesson->media_url ? basename($lesson->media_url) : '' }}',
                remove: 0,
                isHovering: false,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.fileName = file.name;
                        this.preview = URL.createObjectURL(file);
                        this.remove = 0;
                        if(file.type.startsWith('video/')) {
                            this.previewType = 'video';
                        } else if(file.type.startsWith('image/')) {
                            this.previewType = 'image';
                        } else {
                            this.previewType = 'file';
                        }
                    }
                },
                removeMedia() {
                    this.preview = null;
                    this.previewType = null;
                    this.fileName = null;
                    this.remove = 1;
                    document.getElementById('media_input').value = '';
                }}">
                <div class="flex justify-between items-center">
                    <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Media Pembelajaran</label>
                    <span class="text-xs text-text-muted" x-show="!preview">Video/Gambar (Max 100MB)</span>
                </div>
                
                <!-- Hidden Input for Removal Flag -->
                <input type="hidden" name="remove_media" :value="remove">

                <div class="relative w-full h-64 bg-surface-off border-2 transition-all duration-200 group overflow-hidden"
                     :class="preview ? 'border-border' : 'border-dashed border-border hover:border-black'"
                     @dragover.prevent="isHovering = true"
                     @dragleave.prevent="isHovering = false"
                     @drop.prevent="isHovering = false">
                    
                    <!-- File Input -->
                    <input type="file" name="media_url" id="media_input" accept="video/mp4,video/quicktime,image/*" @change="handleFile($event)"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <!-- Empty State -->
                    <div x-show="!preview" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <div class="bg-white p-4 rounded-full border border-border mb-3 shadow-sm group-hover:scale-110 transition-transform">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-text-muted"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                        </div>
                        <span class="text-sm font-bold text-text-main uppercase tracking-wider">Upload Media</span>
                        <span class="text-xs text-text-muted mt-1">Video atau Gambar</span>
                    </div>

                    <!-- Preview State -->
                    <div x-show="preview" class="absolute inset-0 w-full h-full bg-white">
                        <template x-if="previewType === 'image'">
                            <img :src="preview" class="w-full h-full object-cover">
                        </template>
                        
                        <template x-if="previewType === 'video'">
                            <video :src="preview" class="w-full h-full object-cover" muted controls preload="metadata"></video>
                        </template>

                        <template x-if="previewType === 'file'">
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-text-main mb-2 mx-auto"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                    <span class="text-sm font-bold text-text-main" x-text="fileName"></span>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Overlay (Visual Cue) -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                            <div class="bg-black text-white px-4 py-2 rounded-full font-bold uppercase tracking-wider text-xs shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all">
                                Ganti Media
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <button type="button" @click.prevent="removeMedia()" 
                            class="absolute top-4 right-4 bg-red-600 text-white p-2 rounded-full shadow-lg hover:bg-red-700 transition-colors z-50 transform hover:scale-105"
                            style="position: absolute; top: 1rem; right: 1rem; background-color: #dc2626; color: white; opacity: 1;"
                            title="Hapus Media">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                </div>
                @error('media_url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Durasi (Menit)</label>
                    <input type="number" name="duration" value="{{ old('duration', $lesson->duration) }}"
                        class="w-full bg-surface-off border-b-2 border-border p-3 focus:outline-none focus:border-black transition-colors"
                        placeholder="Contoh: 15">
                    @error('duration') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_preview" id="is_preview" value="1" {{ old('is_preview', $lesson->is_preview) ? 'checked' : '' }} class="w-4 h-4 border-border rounded text-black focus:ring-black">
                        <span class="text-sm font-medium text-text-main select-none">Set sebagai Preview Gratis</span>
                    </label>
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-text-main">Konten Materi</label>
                <div class="border-2 border-border bg-white overflow-hidden" style="height: 600px; display: flex; flex-direction: column;">
                    <div id="editor-container" style="height: 100%; display: flex; flex-direction: column;"></div>
                </div>
                <input type="hidden" name="content" id="content-input">
                @error('content') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-6 border-t border-border flex justify-end gap-4">
                <a href="{{ route('lecturer.modules.show', $module) }}" class="px-6 py-3 text-sm font-bold uppercase tracking-wider text-text-muted hover:text-black">Batal</a>
                <button type="submit" class="bg-black text-white px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-accent transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Mulai menulis konten materi pelajaran...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Populate existing content
    var existingContent = {!! json_encode($lesson->content) !!};
    if (existingContent && existingContent !== 'Content') {
        quill.root.innerHTML = existingContent;
    }

    // Handle form submission - capture Quill content
    // IMPORTANT: Use the form that contains the content-input, not the first form on the page!
    var contentInput = document.getElementById('content-input');
    var form = contentInput.closest('form');
    var formSubmitted = false;
    
    form.addEventListener('submit', function(e) {
        if (!formSubmitted) {
            e.preventDefault();
            
            // Get the Quill content
            var content = quill.root.innerHTML;
            
            // Set it in the hidden input
            contentInput.value = content;
            
            console.log('Submitting content:', content.substring(0, 100)); // Debug log
            
            // Mark as submitted and submit the form
            formSubmitted = true;
            form.submit();
        }
    });
</script>
@endsection

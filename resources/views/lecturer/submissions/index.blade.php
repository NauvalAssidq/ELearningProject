@extends('layouts.app')

@section('title', 'Submissions - ' . $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            {{-- Breadcrumb --}}
            <x-breadcrumb :items="[
                ['label' => 'Dasbor', 'url' => route('dashboard')],
                ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
                ['label' => $module->title, 'url' => route('lecturer.modules.show', $module), 'truncate' => true],
                ['label' => 'Submissions'],
            ]" class="mb-2" />
            <h1 class="text-3xl font-bold text-text-main">Project Submissions</h1>
            <p class="text-text-muted mt-1">{{ $module->title }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 border-t border-l border-border bg-white">
        <div class="p-6 border-r border-b border-border">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-2">Total Submissions</p>
            <p class="text-4xl font-light">{{ $submissions->count() }}</p>
        </div>
        <div class="p-6 border-r border-b border-border">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-2">Sudah Dinilai</p>
            <p class="text-4xl font-light text-green-600">{{ $gradedCount }}</p>
        </div>
        <div class="p-6 border-r border-b border-border {{ $pendingCount > 0 ? 'bg-yellow-50' : '' }}">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-2">Belum Dinilai</p>
            <p class="text-4xl font-light {{ $pendingCount > 0 ? 'text-yellow-600' : '' }}">{{ $pendingCount }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Submissions List --}}
    <div class="bg-white border border-border">
        @if($submissions->count() > 0)
            <table class="w-full">
                <thead class="bg-surface-off border-b border-border">
                    <tr>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider text-text-muted">Siswa</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider text-text-muted">File</th>
                        <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider text-text-muted">Submitted</th>
                        <th class="text-center px-6 py-4 text-xs font-bold uppercase tracking-wider text-text-muted">Nilai</th>
                        <th class="text-right px-6 py-4 text-xs font-bold uppercase tracking-wider text-text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($submissions as $submission)
                        <tr class="hover:bg-surface-off transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-text-main">{{ $submission->user->name }}</p>
                                        <p class="text-xs text-text-muted">{{ $submission->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-text-main truncate max-w-[200px]" title="{{ $submission->original_filename }}">
                                    {{ $submission->original_filename }}
                                </p>
                                <p class="text-xs text-text-muted">{{ $submission->file_size_human }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-text-main">{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y') : $submission->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-text-muted">{{ $submission->submitted_at ? $submission->submitted_at->format('H:i') : $submission->created_at->format('H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($submission->grade !== null)
                                    <span class="inline-flex items-center justify-center px-3 py-1 {{ $submission->grade >= 60 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-bold text-lg">
                                        {{ $submission->grade }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold uppercase">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('lecturer.submissions.review', [$module, $submission]) }}" 
                                   class="inline-flex items-center gap-1 text-sm font-bold text-accent hover:underline">
                                    {{ $submission->grade !== null ? 'Detail' : 'Nilai' }}
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-12 text-center text-text-muted">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-3 opacity-50">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <p>Belum ada siswa yang submit project untuk modul ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection

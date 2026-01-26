@extends('layouts.app')

@section('title', 'Peserta Modul - ' . $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Dasbor', 'url' => route('dashboard')],
            ['label' => 'Modul', 'url' => route('lecturer.modules.index')],
            ['label' => $module->title, 'url' => route('lecturer.modules.show', $module), 'truncate' => true],
            ['label' => 'Peserta'],
        ]" class="mb-4" />
        <h1 class="text-3xl font-bold text-text-main mt-2">Peserta Modul</h1>
        <p class="text-text-muted">{{ $module->title }}</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-border p-6">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-2">Total Peserta</p>
            <p class="text-4xl font-bold">{{ $learners->count() }}</p>
        </div>
        <div class="bg-white border border-border p-6">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-2">Level Modul</p>
            <p class="text-2xl font-bold capitalize">{{ $module->skill_level }}</p>
        </div>
        <div class="bg-white border border-border p-6">
            <p class="text-xs text-text-muted uppercase tracking-widest mb-2">Total Pelajaran</p>
            <p class="text-4xl font-bold">{{ $module->lessons()->count() }}</p>
        </div>
    </div>

    {{-- Learners Table --}}
    @if($learners->count() > 0)
    <div class="bg-white border border-border">
        <table class="w-full">
            <thead class="bg-surface-off border-b border-border">
                <tr>
                    <th class="text-left p-4 text-xs uppercase tracking-widest text-text-muted font-semibold">Nama</th>
                    <th class="text-left p-4 text-xs uppercase tracking-widest text-text-muted font-semibold">Email</th>
                    <th class="text-left p-4 text-xs uppercase tracking-widest text-text-muted font-semibold">Level</th>
                    <th class="text-left p-4 text-xs uppercase tracking-widest text-text-muted font-semibold">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach($learners as $learner)
                <tr class="hover:bg-surface-off transition-colors">
                    <td class="p-4 font-medium">{{ $learner->name }}</td>
                    <td class="p-4 text-text-muted">{{ $learner->email }}</td>
                    <td class="p-4">
                        @php
                            $levelColors = [
                                'pemula' => 'bg-green-100 text-green-800',
                                'menengah' => 'bg-yellow-100 text-yellow-800',
                                'mahir' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold {{ $levelColors[$learner->skill_level] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($learner->skill_level ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="p-4 text-text-muted text-sm">
                        {{ $learner->pivot->enrolled_at ? \Carbon\Carbon::parse($learner->pivot->enrolled_at)->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="p-4 text-right">
                        <form action="{{ route('lecturer.modules.kick', [$module, $learner]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan siswa ini dari modul?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition-colors">
                                KELUARKAN
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-12 text-center text-text-muted border border-dashed border-border bg-white">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-3 opacity-50">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <p>Belum ada peserta yang mendaftar ke modul ini.</p>
    </div>
    @endif
</div>
@endsection

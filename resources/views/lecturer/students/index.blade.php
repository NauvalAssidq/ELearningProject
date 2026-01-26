@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Dasbor', 'url' => route('dashboard')],
                ['label' => 'Daftar Siswa'],
            ]" class="mb-2" />
            <h1 class="text-3xl font-bold text-text-main">Daftar Siswa</h1>
            <p class="text-text-muted mt-1">Pantau kemajuan belajar dan nilai siswa yang terdaftar di modul Anda.</p>
        </div>
    </div>

    @if($students->count() > 0)
    <div class="bg-white border border-border" x-data="{ expanded: null }">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border bg-surface-off text-xs uppercase tracking-widest text-text-muted">
                        <th class="p-6 font-bold">Mahasiswa</th>
                        <th class="p-6 font-bold text-center">Modul Diambil</th>
                        <th class="p-6 font-bold w-1/3">Progres Rata-rata</th>
                        <th class="p-6 font-bold text-center">Nilai Saat Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($students as $student)
                    <tr class="group hover:bg-surface-off transition-colors cursor-pointer" 
                        @click="expanded === {{ $student->id }} ? expanded = null : expanded = {{ $student->id }}">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-text-main group-hover:text-accent transition-colors">{{ $student->name }}</p>
                                    <p class="text-xs text-text-muted">{{ $student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 bg-surface-off border border-border text-xs font-bold rounded-full">
                                {{ $student->enrolled_count }} Modul
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-accent transition-all duration-500" 
                                         style="width: {{ $student->avg_progress }}%"></div>
                                </div>
                                <span class="text-sm font-bold w-12 text-right">{{ $student->avg_progress }}%</span>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="text-xl font-bold {{ $student->avg_grade >= 80 ? 'text-green-600' : ($student->avg_grade >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $student->avg_grade }}
                                </span>
                                <span class="text-[10px] uppercase tracking-wider text-text-muted">RATUSAN</span>
                            </div>
                        </td>
                    </tr>
                    <!-- Detail Row -->
                    <tr x-show="expanded === {{ $student->id }}" x-cloak class="bg-gray-50 border-b border-border">
                        <td colspan="4" class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($student->modules_data as $mod)
                                <div class="bg-white border border-border p-4">
                                    <h4 class="font-bold text-sm mb-3 truncate">{{ $mod['title'] }}</h4>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <div class="flex justify-between text-xs text-text-muted mb-1">
                                                <span>Progres</span>
                                                <span class="font-bold">{{ $mod['progress'] }}%</span>
                                            </div>
                                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-accent" style="width: {{ $mod['progress'] }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-end border-t border-dashed border-gray-200 pt-2">
                                            <span class="text-xs text-text-muted">Nilai Akhir</span>
                                            <span class="font-bold text-lg {{ $mod['grade'] >= 80 ? 'text-green-600' : ($mod['grade'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $mod['grade'] }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 text-right">
                                        <a href="{{ route('lecturer.modules.learners', ['module' => $mod['slug']]) }}" class="text-xs font-bold text-accent hover:underline">
                                            Kelola Peserta →
                                        </a>
                                    </div>
                                </div>
                                @empty
                                <div class="col-span-full text-center py-4 text-text-muted italic">
                                    Tidak ada data modul.
                                </div>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="p-16 text-center border-2 border-dashed border-border bg-white rounded-lg">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-gray-300">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <h3 class="text-lg font-bold text-text-main mb-2">Belum Ada Siswa</h3>
        <p class="text-text-muted max-w-md mx-auto">
            Belum ada siswa yang mendaftar di modul-modul yang Anda buat. Bagikan modul Anda untuk mulai mendapatkan siswa.
        </p>
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dasbor Dosen')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[
        ['label' => 'Dasbor'],
    ]" />

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-4xl font-semibold text-text-main mb-2">Panel Dosen</h2>
            <p class="text-text-muted">Kelola modul, soal kuis, dan pantau kemajuan siswa.</p>
        </div>
        <a href="{{ route('lecturer.modules.create') }}" 
           class="inline-flex items-center gap-2 bg-black text-white px-6 py-3 font-bold hover:bg-gray-800 transition-colors">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Buat Modul Baru
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 border border-border flex items-center justify-between group hover:border-black transition-colors">
            <div>
                <p class="text-xs text-text-muted uppercase tracking-widest mb-1">Total Modul</p>
                <h3 class="text-3xl font-bold text-text-main group-hover:text-accent transition-colors">{{ $totalModules }}</h3>
            </div>
            <div class="w-12 h-12 bg-surface-off flex items-center justify-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-text-muted group-hover:text-black transition-colors">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 border border-border flex items-center justify-between group hover:border-black transition-colors">
            <div>
                <p class="text-xs text-text-muted uppercase tracking-widest mb-1">Tingkat Kelulusan</p>
                <h3 class="text-3xl font-bold text-text-main group-hover:text-accent transition-colors">{{ $completionStats['rate'] }}%</h3>
            </div>
            <div class="w-12 h-12 bg-surface-off flex items-center justify-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-text-muted group-hover:text-black transition-colors">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 border border-border flex items-center justify-between group hover:border-black transition-colors">
            <div>
                <p class="text-xs text-text-muted uppercase tracking-widest mb-1">Siswa Terdaftar</p>
                <h3 class="text-3xl font-bold text-text-main group-hover:text-accent transition-colors">{{ $totalEnrolledStudents }}</h3>
            </div>
            <div class="w-12 h-12 bg-surface-off flex items-center justify-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-text-muted group-hover:text-black transition-colors">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Learning Effectiveness Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- 1. Grade Distribution (Bar) -->
        <div class="lg:col-span-2 bg-white border border-border p-6 hover:border-black transition-colors">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    Distribusi Pemahaman Siswa
                </h3>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded text-text-muted">Efektivitas Konten</span>
            </div>
            <p class="text-sm text-text-muted mb-4">Grafik ini menunjukkan seberapa baik siswa memahami materi berdasarkan nilai kuis mereka. Kurva yang condong ke kanan (Excellent) menandakan materi efektif.</p>
            <div id="gradeDistChart" class="w-full h-72"></div>
        </div>

        <!-- 2. Completion Funnel (Donut) -->
        <div class="bg-white border border-border p-6 hover:border-black transition-colors">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                </svg>
                Tingkat Penyelesaian
            </h3>
            <div id="completionChart" class="w-full h-64 flex items-center justify-center"></div>
            <div class="mt-4 text-center">
                <p class="text-sm text-text-muted">Dari <span class="font-bold text-black">{{ $totalEnrolledStudents }}</span> siswa, <span class="font-bold text-green-600">{{ $completionStats['completed'] }}</span> telah menyelesaikan modul.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-2 bg-white border border-border p-6 hover:border-black transition-colors">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                </svg>
                Skor Efektivitas Modul
            </h3>
            <p class="text-sm text-text-muted mb-6">Skor rata-rata performa siswa per modul. Semakin tinggi skor, semakin efektif modul dalam menyampaikan materi.</p>
            <div id="effectivenessChart" class="w-full h-80"></div>
        </div>
        <div class="lg:col-span-2 bg-white border border-border h-fit">
            <div class="p-6 border-b border-border flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-lg uppercase tracking-wide">Siswa Terbaru</h3>
                <a href="{{ route('lecturer.students.index') }}" class="text-xs font-bold underline hover:text-accent">Lihat Semua</a>
            </div>
            <div class="divide-y divide-border">
                @forelse($recentEnrollments as $enrollment)
                    <div class="p-4 flex items-center gap-4 hover:bg-surface-off transition-colors">
                        <div class="w-10 h-10 bg-black text-white flex items-center justify-center text-sm font-bold">
                            {{ substr($enrollment->user->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-sm truncate">{{ $enrollment->user->name }}</h4>
                            <p class="text-xs text-text-muted truncate">Mendaftar di {{ $enrollment->module->title }}</p>
                        </div>
                        <div class="text-xs font-mono text-text-muted">
                            {{ $enrollment->created_at->diffForHumans(null, true) }}
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-text-muted text-sm italic">
                        Belum ada siswa yang mendaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Grade Distribution (Bar Chart)
        const gradeOptions = {
            series: [{
                name: 'Jumlah Siswa',
                data: [{{ $gradeDistribution['needs_help'] }}, {{ $gradeDistribution['passing'] }}, {{ $gradeDistribution['excellent'] }}]
            }],
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                    distributed: true,
                }
            },
            colors: ['#EF4444', '#e69138', '#10B981'], // Red, Accent(Yellowish), Green
            xaxis: {
                categories: ['Butuh Bimbingan (<60)', 'Kompeten (60-84)', 'Sangat Baik (≥85)'],
                labels: { style: { colors: '#6B7280', fontSize: '12px', fontWeight: 600 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                title: { text: 'Jumlah Siswa' }
            },
            legend: { show: false },
            grid: { borderColor: '#F3F4F6' }
        };
        new ApexCharts(document.querySelector("#gradeDistChart"), gradeOptions).render();

        // 2. Completion Funnel (Donut Chart)
        const completionOptions = {
            series: [{{ $completionStats['active'] }}, {{ $completionStats['completed'] }}],
            labels: ['Sedang Belajar', 'Lulus / Selesai'],
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
            },
            colors: ['#E5E7EB', '#111827'], // Grey, Black
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Tingkat Kelulusan',
                                formatter: () => '{{ $completionStats['rate'] }}%'
                            }
                        }
                    }
                }
            },
            legend: { position: 'bottom' },
            dataLabels: { enabled: false },
            stroke: { show: false }
        };
        new ApexCharts(document.querySelector("#completionChart"), completionOptions).render();

        // 3. Module Effectiveness (Bar Chart)
        const effectivenessOptions = {
            series: [{
                name: 'Skor Efektivitas',
                data: @json($moduleEffectiveness->pluck('score'))
            }],
            chart: {
                type: 'bar',
                height: 320,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 0,
                    horizontal: true,
                    barHeight: '60%',
                }
            },
            colors: ['#111827'],
            xaxis: {
                categories: @json($moduleEffectiveness->pluck('title')),
                max: 100,
                labels: { style: { colors: '#6B7280' } }
            },
            grid: { borderColor: '#F3F4F6', strokeDashArray: 4 },
            tooltip: {
                y: { formatter: (val) => val + " / 100" }
            }
        };
        new ApexCharts(document.querySelector("#effectivenessChart"), effectivenessOptions).render();
    });
</script>
</div>
@endsection

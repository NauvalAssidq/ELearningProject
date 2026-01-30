@extends('layouts.app')

@section('title', 'Asesmen Level Up')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Dasbor', 'url' => route('dashboard')],
        ['label' => 'Asesmen Level Up'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-black mb-2">Asesmen Level Up</h1>
        <p class="text-gray-600">Uji kemampuan Anda untuk naik ke level berikutnya.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 font-medium">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 mb-6 font-medium">
            {{ session('info') }}
        </div>
    @endif

    <!-- Current Status Card -->
    <div class="bg-white border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Level Saat Ini</p>
                <p class="text-2xl font-bold text-black">{{ ucfirst($user->skill_level) }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Target Level</p>
                <p class="text-2xl font-bold text-black">{{ ucfirst($nextLevel) }}</p>
            </div>
        </div>

        <!-- Progress Arrow -->
        <div class="flex items-center justify-center gap-4 py-4">
            @php
                $levels = ['pemula', 'menengah', 'mahir'];
                $currentIndex = array_search($user->skill_level, $levels);
            @endphp
            @foreach($levels as $index => $level)
                <div class="flex items-center">
                    <div class="w-20 h-20 rounded-full border-2 flex items-center justify-center text-sm font-bold uppercase
                        {{ $index < $currentIndex ? 'bg-green-500 text-white border-green-500' : '' }}
                        {{ $index == $currentIndex ? 'bg-black text-white border-black' : '' }}
                        {{ $index > $currentIndex ? 'bg-gray-100 text-gray-400 border-gray-200' : '' }}">
                        {{ ucfirst($level) }}
                    </div>
                    @if($index < count($levels) - 1)
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mx-2 text-gray-300">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Assessment Info Card -->
    <div class="bg-white border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-lg mb-4">Tentang Asesmen</h2>
        <ul class="space-y-3 text-gray-700">
            <li class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-black mt-0.5 shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>15 soal</strong> pilihan ganda dari Bank Soal level {{ ucfirst($user->skill_level) }}</span>
            </li>
            <li class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-black mt-0.5 shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Passing grade: <strong>75%</strong> (minimal 12 benar)</span>
            </li>
            <li class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-black mt-0.5 shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Jika gagal, Anda dapat mengulang setelah <strong>7 hari</strong></span>
            </li>
        </ul>
    </div>

    <!-- Action Section -->
    @if($canTakeAssessment)
        <form action="{{ route('assessment.start') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-black text-white py-4 font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition-colors">
                Mulai Asesmen
            </button>
        </form>
    @else
        <div class="bg-gray-100 border border-gray-200 p-6 text-center">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-4 text-gray-400">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <p class="text-gray-600 mb-2">Anda dalam masa cooldown.</p>
            <p class="text-2xl font-bold text-black">{{ $daysRemaining }} hari lagi</p>
            <p class="text-sm text-gray-500 mt-2">hingga Anda dapat mengulang asesmen.</p>
        </div>
    @endif

    <!-- Last Attempt Info -->
    @if($lastAttempt)
        <div class="mt-6 p-4 bg-gray-50 border border-gray-200">
            <p class="text-xs uppercase tracking-widest text-gray-500 mb-2">Percobaan Terakhir</p>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">{{ $lastAttempt->created_at->format('d M Y, H:i') }}</span>
                <span class="font-bold {{ $lastAttempt->passed ? 'text-green-600' : 'text-red-600' }}">
                    {{ $lastAttempt->score }}% - {{ $lastAttempt->passed ? 'LULUS' : 'GAGAL' }}
                </span>
            </div>
        </div>
    @endif

</div>
@endsection

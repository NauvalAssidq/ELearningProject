@extends('layouts.app')

@section('title', 'Panduan Sistem')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">
    
    <!-- Article Header -->
    <header class="mb-10">
        <p class="text-sm font-medium text-gray-500 mb-3">Dokumentasi &bull; Manual Referensi V1.0</p>
        <h1 class="text-3xl font-bold text-black mb-4" style="line-height: 1.4;">
            Panduan Penggunaan Sistem TechALearn
        </h1>
        <p class="text-base text-gray-600" style="line-height: 1.6;">
            Dokumen ini menjelaskan cara kerja sistem pembelajaran adaptif, mulai dari tes penempatan hingga validasi proyek akhir oleh dosen.
        </p>
    </header>

    <hr class="border-gray-200 mb-10">

    <!-- Main Content -->
    <div class="space-y-10">

        <!-- Section 1 -->
        <section>
            <h2 class="text-xl font-bold text-black mb-4">1. Tes Penempatan</h2>
            <p class="text-base text-gray-700 mb-4" style="line-height: 1.6;">
                Sistem TechALearn menggunakan <strong>Algoritma Penempatan Adaptif</strong> untuk menentukan titik awal pembelajaran Anda. Fitur ini dirancang untuk mencegah redundansi materi bagi siswa yang sudah mahir dan memberikan fondasi kuat bagi pemula.
            </p>
            <p class="text-base text-gray-700 mb-4" style="line-height: 1.6;">
                Saat pengguna baru pertama kali login, sistem akan men-trigger middleware <code class="bg-gray-100 px-1 rounded text-sm">PlacementTestCheck</code>. Jika pengguna belum memiliki record nilai, akses ke modul akan diblokir dan diredirect ke halaman ujian. Skor akhir dikalkulasi menggunakan bobot kesulitan soal, bukan sekadar jumlah benar.
            </p>
            
            <h3 class="text-lg font-semibold text-black mt-6 mb-3">Cara Mengerjakan Tes</h3>
            <p class="text-base text-gray-700 mb-4" style="line-height: 1.6;">
                Untuk memulai proses kalibrasi, arahkan kursor ke notifikasi di dashboard utama. Sistem memberikan waktu 60 menit untuk 20 soal teknis. Setelah submit, backend akan memproses level Anda:
            </p>
            <ul class="list-disc pl-6 text-base text-gray-700 space-y-2" style="line-height: 1.6;">
                <li><strong>Beginner (0-40)</strong>: Fokus pada sintaks dasar dan logika pemrograman.</li>
                <li><strong>Intermediate (41-70)</strong>: Fokus pada struktur data dan penerapan framework.</li>
                <li><strong>Advanced (71-100)</strong>: Fokus pada arsitektur sistem dan optimasi performa.</li>
            </ul>
        </section>

        <!-- Section 2 -->
        <section>
            <h2 class="text-xl font-bold text-black mb-4">2. Arsitektur Modul Sekuensial</h2>
            <p class="text-base text-gray-700 mb-4" style="line-height: 1.6;">
                Modul pembelajaran disusun menggunakan struktur <strong>Dependency Chain</strong>. Artinya, sebuah modul (Modul B) memiliki dependensi keras terhadap modul sebelumnya (Modul A). Hal ini memastikan integritas pemahaman siswa sebelum lanjut ke materi kompleks.
            </p>

            <h3 class="text-lg font-semibold text-black mt-6 mb-3">Protokol Penyelesaian</h3>
            <p class="text-base text-gray-700 mb-4" style="line-height: 1.6;">
                Setiap modul terdiri dari dua komponen utama: Materi (Lesson) dan Evaluasi (Quiz). Progress tracking dilakukan secara real-time pada database.
            </p>
            <p class="text-base text-gray-700 mb-4" style="line-height: 1.6;">
                <strong>Langkah 1: Akuisisi Materi</strong><br>
                Buka tab "Modul Saya". Klik tombol "Pelajari". Sistem akan merender materi dalam format PDF atau Video Embed. Wajib klik tombol "Tandai Selesai" untuk mengirim update status ke server.
            </p>
            <p class="text-base text-gray-700 mb-4" style="line-height: 1.6;">
                <strong>Langkah 2: Validasi Pemahaman</strong><br>
                Setelah seluruh materi 100% viewed, akses Kuis Evaluasi akan dibuka. Kuis ini menggunakan randomized question bank untuk mencegah kecurangan. Ambang batas kelulusan (Passing Grade) adalah <strong>70</strong>. User yang gagal harus menunggu 24 jam sebelum retake.
            </p>
        </section>

        <!-- Section 3 -->
        <section>
            <h2 class="text-xl font-bold text-black mb-4">3. Kontrol Akademik & Validasi</h2>
            <p class="text-base text-gray-700 mb-4" style="line-height: 1.6;">
                Peran Dosen (Lecturer) dalam sistem ini adalah sebagai Validator Absolut. Sementara kuis dinilai oleh mesin, Proyek Akhir memerlukan review manual untuk memastikan kualitas kode dan penerapan best practice.
            </p>

            <h3 class="text-lg font-semibold text-black mt-6 mb-3">Workflow Penilaian Proyek</h3>
            <ol class="list-decimal pl-6 text-base text-gray-700 space-y-3" style="line-height: 1.6;">
                <li><strong>Submission:</strong> Mahasiswa mengunggah repository link (GitHub/GitLab) pada form submission akhir modul.</li>
                <li><strong>Review:</strong> Dosen menerima notifikasi pada dashboard "Pending Review". Dosen memeriksa struktur kode, kebersihan (clean code), dan fungsionalitas.</li>
                <li><strong>Decision:</strong> Dosen menginput nilai (0-100) dan feedback tekstual. Jika nilai >= 70, sertifikat digital digenerate secara otomatis.</li>
            </ol>
            
            <p class="text-base text-gray-700 mt-4" style="line-height: 1.6;">
                Fitur "God Mode" memungkinkan dosen untuk memantau progress setiap individu secara granular, melihat log aktivitas, dan melakukan intervensi (seperti reset kuis) jika terjadi kendala teknis pada sisi mahasiswa.
            </p>
        </section>

    </div>

    <!-- Article Footer -->
    <div class="mt-12 pt-8 border-t border-gray-200 flex justify-between items-center text-sm text-gray-500">
        <p>TechALearn Engineering</p>
        <p>Last Updated: January 2026</p>
    </div>

</div>
@endsection

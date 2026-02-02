<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta -->
    <title>TechALearn - Platform Pembelajaran Teknologi | UIN Ar-Raniry</title>
    <meta name="description" content="Platform pembelajaran berbasis proyek untuk mahasiswa Teknologi Informasi UIN Ar-Raniry Banda Aceh. Belajar dengan metode modern dan praktis.">

    <!-- Preconnect for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Critical CSS inline for FCP -->
    <style>
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    </style>

    <!-- Fonts - async loading -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Animation Styles -->
    <style>
        /* Keyframe Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* Animation Classes */
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .animate-slide-left {
            animation: slideInLeft 0.6s ease-out forwards;
        }
        
        .animate-slide-right {
            animation: slideInRight 0.6s ease-out forwards;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.6s ease-out forwards;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Stagger delays */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        
        /* Initial hidden state for scroll animations */
        .scroll-animate {
            opacity: 0;
        }
        
        .scroll-animate.animated {
            opacity: 1;
        }
        
        /* Hero specific */
        .hero-text { animation: fadeInUp 0.8s ease-out 0.2s forwards; opacity: 0; }
        .hero-subtitle { animation: fadeInUp 0.8s ease-out 0.4s forwards; opacity: 0; }
        .hero-buttons { animation: fadeInUp 0.8s ease-out 0.6s forwards; opacity: 0; }
        .hero-visual { animation: slideInRight 0.8s ease-out 0.5s forwards; opacity: 0; }
        
        /* Smooth transitions */
        .transition-smooth {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Hover lift effect */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-surface font-sans text-text-main antialiased selection:bg-accent selection:text-white"
    x-data="{ mobileMenuOpen: false }">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-border">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <img src="{{ asset('storage/assets/favicon.ico') }}" class="h-25 w-auto object-contain mix-blend-multiply" alt="Prodi TI Logo">
                    </div>
                    <span class="font-bold text-xl tracking-tight">TECHALEARN</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-text-muted hover:text-text-main transition-colors">Fitur</a>
                    <a href="#about" class="text-sm font-medium text-text-muted hover:text-text-main transition-colors">Tentang</a>
                    <a href="#contact" class="text-sm font-medium text-text-muted hover:text-text-main transition-colors">Kontak</a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-black text-white px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-accent transition-colors flex items-center gap-2">
                            <span>Dashboard</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-text-main hover:text-accent transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-black text-white px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-accent transition-colors">
                            Daftar
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 border border-border">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-white border-t border-border">
            <div class="px-6 py-4 space-y-4">
                <a href="#features" class="block text-sm font-medium text-text-muted hover:text-text-main">Fitur</a>
                <a href="#about" class="block text-sm font-medium text-text-muted hover:text-text-main">Tentang</a>
                <a href="#contact" class="block text-sm font-medium text-text-muted hover:text-text-main">Kontak</a>
                <div class="pt-4 border-t border-border space-y-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-center py-3 text-sm font-medium bg-black text-white hover:bg-accent transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block text-center py-3 text-sm font-medium border border-border hover:border-black transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="block text-center py-3 text-sm font-medium bg-black text-white hover:bg-accent transition-colors">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center pt-20 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-surface-off">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-accent/5 to-transparent"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20 border-l border-r border-gray-300 border-dashed w-full">
            <div>
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Content -->
                    <div class="space-y-8">
                        <div class="space-y-2 hero-text">
                            <p class="text-xs font-bold uppercase tracking-widest text-accent">Teknologi Informasi • UIN Ar-Raniry</p>
                            <h1 class="text-5xl lg:text-7xl font-bold tracking-tight leading-none">
                                BELAJAR<br>
                                TEKNOLOGI<br>
                                <span class="text-accent">DENGAN PROYEK.</span>
                            </h1>
                        </div>
                        <p class="text-lg text-text-muted max-w-lg leading-relaxed hero-subtitle">
                            Platform pembelajaran berbasis proyek yang dirancang khusus untuk mahasiswa Teknologi Informasi UIN Ar-Raniry Banda Aceh. Belajar dengan metode modern, praktis, dan relevan dengan industri.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 hero-buttons">
                            <a href="{{ route('register') }}" class="bg-black text-white px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-accent transition-colors text-center">
                                Mulai Belajar
                            </a>
                            <a href="#features" class="border border-black text-black px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors text-center">
                                Pelajari Lebih
                            </a>
                        </div>
                    </div>

                    <!-- Right Visual -->
                    <div class="hidden lg:block hero-visual">
                        <div class="relative">
                            <!-- Main Card -->
                            <div class="bg-white border border-border p-8 shadow-xl">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 bg-accent/10 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-text-muted uppercase tracking-wide">Current Module</p>
                                        <p class="font-semibold">Laravel Authentication</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="h-2 bg-surface-off rounded-full overflow-hidden">
                                        <div class="h-full bg-accent w-3/4 rounded-full"></div>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-text-muted">Progress</span>
                                        <span class="font-bold">75%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Floating Elements -->
                            <div class="absolute -top-4 -right-4 bg-black text-white px-4 py-2 text-xs font-bold uppercase animate-float">
                                Live
                            </div>
                            <div class="absolute -bottom-6 -left-6 bg-surface-off border border-border px-6 py-4 animate-float delay-300">
                                <p class="text-2xl font-bold text-accent">A+</p>
                                <p class="text-xs text-text-muted">Grade Average</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stats -->
                <div class="pt-12 mt-12 border-t border-border">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Stat 1 -->
                        <div class="observe-animate opacity-0">
                            <div class="bg-white border border-border p-6 group hover:border-accent transition-colors hover-lift h-full">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-black text-white flex items-center justify-center group-hover:bg-accent transition-colors">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-text-muted">Modul</p>
                                </div>
                                <p class="text-4xl font-bold">20<span class="text-accent">+</span></p>
                            </div>
                        </div>
                        
                        <!-- Stat 2 -->
                        <div class="observe-animate opacity-0" data-animate="animate-fade-in-up delay-100">
                            <div class="bg-white border border-border p-6 group hover:border-accent transition-colors hover-lift h-full">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-black text-white flex items-center justify-center group-hover:bg-accent transition-colors">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-text-muted">Mahasiswa</p>
                                </div>
                                <p class="text-4xl font-bold">100<span class="text-accent">+</span></p>
                            </div>
                        </div>
                        
                        <!-- Stat 3 -->
                        <div class="observe-animate opacity-0" data-animate="animate-fade-in-up delay-200">
                            <div class="bg-white border border-border p-6 group hover:border-accent transition-colors hover-lift h-full">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-black text-white flex items-center justify-center group-hover:bg-accent transition-colors">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="3" y1="9" x2="21" y2="9"></line>
                                            <line x1="9" y1="21" x2="9" y2="9"></line>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-text-muted">Proyek</p>
                                </div>
                                <p class="text-4xl font-bold">10<span class="text-accent">+</span></p>
                            </div>
                        </div>
                        
                        <!-- Stat 4 -->
                        <div class="observe-animate opacity-0" data-animate="animate-fade-in-up delay-300">
                            <div class="bg-white border border-border p-6 group hover:border-accent transition-colors hover-lift h-full">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-black text-white flex items-center justify-center group-hover:bg-accent transition-colors">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="8" r="7"></circle>
                                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-text-muted">Sertifikat</p>
                                </div>
                                <p class="text-4xl font-bold">50<span class="text-accent">+</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-white border-t border-b border-dashed border-gray-300">
        <div class="py-24 max-w-7xl mx-auto px-6 lg:px-8 border-l border-r border-gray-300 border-dashed w-full">
            <div class="text-center mb-16 observe-animate opacity-0">
                <p class="text-xs font-bold uppercase tracking-widest text-accent mb-4">Fitur Platform</p>
                <h2 class="text-4xl lg:text-5xl font-bold tracking-tight">
                    Metode Pembelajaran<br>
                    <span class="text-text-muted">Yang Efektif</span>
                </h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-0 border-t border-l border-border">
                <!-- Feature 1 -->
                <div class="border-r border-b border-border observe-animate opacity-0">
                    <div class="p-8 lg:p-12 group hover:bg-surface-off transition-colors h-full">
                        <div class="w-14 h-14 bg-black text-white flex items-center justify-center mb-6 group-hover:bg-accent transition-colors">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Modul Terstruktur</h3>
                        <p class="text-text-muted leading-relaxed">
                            Materi pembelajaran disusun secara sistematis dengan tingkat kesulitan bertahap sesuai kurikulum.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="border-r border-b border-border observe-animate opacity-0" data-animate="animate-fade-in-up delay-100">
                    <div class="p-8 lg:p-12 group hover:bg-surface-off transition-colors h-full">
                        <div class="w-14 h-14 bg-black text-white flex items-center justify-center mb-6 group-hover:bg-accent transition-colors">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Kuis Interaktif</h3>
                        <p class="text-text-muted leading-relaxed">
                            Evaluasi pemahaman dengan kuis interaktif di setiap akhir pelajaran untuk memastikan penguasaan materi.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="border-r border-b border-border observe-animate opacity-0" data-animate="animate-fade-in-up delay-200">
                    <div class="p-8 lg:p-12 group hover:bg-surface-off transition-colors h-full">
                        <div class="w-14 h-14 bg-black text-white flex items-center justify-center mb-6 group-hover:bg-accent transition-colors">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="9" y1="21" x2="9" y2="9"></line>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Project-Based Learning</h3>
                        <p class="text-text-muted leading-relaxed">
                            Belajar dengan mengerjakan proyek nyata untuk membangun portofolio profesional.
                        </p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="border-r border-b border-border observe-animate opacity-0" data-animate="animate-fade-in-up delay-300">
                    <div class="p-8 lg:p-12 group hover:bg-surface-off transition-colors h-full">
                        <div class="w-14 h-14 bg-black text-white flex items-center justify-center mb-6 group-hover:bg-accent transition-colors">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Tes Penempatan</h3>
                        <p class="text-text-muted leading-relaxed">
                            Sistem penempatan otomatis berdasarkan kemampuan awal untuk pengalaman belajar yang personal.
                        </p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="border-r border-b border-border observe-animate opacity-0" data-animate="animate-fade-in-up delay-400">
                    <div class="p-8 lg:p-12 group hover:bg-surface-off transition-colors h-full">
                        <div class="w-14 h-14 bg-black text-white flex items-center justify-center mb-6 group-hover:bg-accent transition-colors">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="7"></circle>
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Sertifikat</h3>
                        <p class="text-text-muted leading-relaxed">
                            Dapatkan sertifikat digital setelah menyelesaikan modul sebagai bukti kompetensi.
                        </p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="border-r border-b border-border observe-animate opacity-0" data-animate="animate-fade-in-up delay-500">
                    <div class="p-8 lg:p-12 group hover:bg-surface-off transition-colors h-full">
                        <div class="w-14 h-14 bg-black text-white flex items-center justify-center mb-6 group-hover:bg-accent transition-colors">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Tracking Progress</h3>
                        <p class="text-text-muted leading-relaxed">
                            Pantau kemajuan belajar secara detail dengan dashboard analytics yang komprehensif.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-surface-off">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left -->
                <!-- Left -->
                <div class="observe-animate opacity-0">
                    <p class="text-xs font-bold uppercase tracking-widest text-accent mb-4">Tentang Platform</p>
                    <h2 class="text-4xl lg:text-5xl font-bold tracking-tight mb-6">
                        Teknologi Informasi<br>
                        <span class="text-text-muted">UIN Ar-Raniry</span>
                    </h2>
                    <div class="space-y-4 text-text-muted leading-relaxed">
                        <p>
                            TechALearn adalah platform pembelajaran digital yang dikembangkan oleh Program Studi Teknologi Informasi, Fakultas Sains dan Teknologi, UIN Ar-Raniry Banda Aceh.
                        </p>
                        <p>
                            Platform ini dirancang untuk mendukung proses pembelajaran berbasis proyek (Project-Based Learning) yang mengintegrasikan teori dan praktik secara efektif.
                        </p>
                        <p>
                            Dengan TechALearn, mahasiswa dapat belajar teknologi terkini dengan metode yang relevan dengan kebutuhan industri, membangun portfolio proyek, dan mengembangkan skill yang dibutuhkan di dunia kerja.
                        </p>
                    </div>
                </div>

                <!-- Right -->
                <div class="bg-white border border-border p-8 lg:p-12 observe-animate opacity-0"
                     data-animate="animate-fade-in-up delay-200">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <p class="text-4xl font-bold text-accent">2024</p>
                            <p class="text-sm text-text-muted">Tahun Berdiri</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-4xl font-bold">100%</p>
                            <p class="text-sm text-text-muted">Online Learning</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-4xl font-bold">24/7</p>
                            <p class="text-sm text-text-muted">Akses Materi</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-4xl font-bold text-accent">Free</p>
                            <p class="text-sm text-text-muted">Untuk Mahasiswa</p>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-border">
                        <p class="text-sm text-text-muted">
                            <span class="font-bold text-text-main">Alamat:</span><br>
                            Fakultas Sains dan Teknologi<br>
                            UIN Ar-Raniry Banda Aceh<br>
                            Jl. Syeikh Abdul Rauf, Kopelma Darussalam
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-black text-white">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="text-4xl lg:text-5xl font-bold tracking-tight mb-6 observe-animate opacity-0">
                Siap Untuk Mulai<br>
                <span class="text-accent">Belajar?</span>
            </h2>
            <p class="text-lg text-gray-300 mb-10 max-w-2xl mx-auto observe-animate opacity-0"
               data-animate="animate-fade-in-up delay-100">
                Daftarkan dirimu sekarang dan mulai perjalanan belajar teknologi dengan metode project-based learning.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center observe-animate opacity-0"
                 data-animate="animate-fade-in-up delay-200">
                <a href="{{ route('register') }}" class="bg-white text-black px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-accent hover:text-white transition-colors">
                    Daftar Sekarang
                </a>
                <a href="#contact" class="border border-white text-white px-8 py-4 text-sm font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-colors">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-surface-off border-t border-border relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-gray-100 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-12 gap-16">
                <!-- Header -->
                <div class="lg:col-span-4 space-y-6 observe-animate opacity-0">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-accent mb-4">Panduan</p>
                        <h2 class="text-4xl font-bold tracking-tight mb-4">
                            Alur<br>
                            <span class="text-text-muted">Pembelajaran</span>
                        </h2>
                    </div>
                    <p class="text-text-muted leading-relaxed">
                        Pahami tahapan yang akan Anda lalui mulai dari pendaftaran hingga mendapatkan sertifikasi kompetensi.
                    </p>
                    <a href="#contact" class="inline-flex items-center gap-2 font-bold border-b border-black pb-1 hover:text-accent hover:border-accent transition-colors">
                        Hubungi Kami
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>

                <!-- Accordion -->
                <div class="lg:col-span-8 w-full border-t border-gray-200 min-h-[500px]" x-data="{ active: 1 }">
                    <!-- Q1 -->
                    <div class="border-b border-gray-200 observe-animate opacity-0" data-animate="animate-fade-in-up delay-100">
                        <button @click="active === 1 ? active = null : active = 1" class="w-full flex items-center justify-between py-6 text-left group hover:bg-gray-50/50 transition-colors">
                            <span class="font-bold text-lg pr-8 text-black group-hover:text-accent transition-colors">Mengapa saya harus mengikuti Tes Penempatan?</span>
                            <span class="flex-shrink-0 ml-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-black text-white transition-colors group-hover:bg-accent">
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-45': active === 1}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            </span>
                        </button>
                        <div x-show="active === 1" x-collapse x-cloak>
                            <div class="pb-6 text-text-muted leading-relaxed">
                                Tes ini dirancang untuk mengkalibrasi kurikulum dengan kemampuan teknis Anda saat ini. Hasil tes memastikan Anda mendapatkan materi yang menantang namun dapat dikuasai, baik di level Pemula, Menengah, maupun Mahir.
                            </div>
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="border-b border-gray-200 observe-animate opacity-0" data-animate="animate-fade-in-up delay-200">
                        <button @click="active === 2 ? active = null : active = 2" class="w-full flex items-center justify-between py-6 text-left group hover:bg-gray-50/50 transition-colors">
                            <span class="font-bold text-lg pr-8 text-black group-hover:text-accent transition-colors">Bagaimana sistem Modul Adaptif bekerja?</span>
                            <span class="flex-shrink-0 ml-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-black text-white transition-colors group-hover:bg-accent">
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-45': active === 2}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            </span>
                        </button>
                        <div x-show="active === 2" x-collapse x-cloak>
                            <div class="pb-6 text-text-muted leading-relaxed">
                                Berdasarkan hasil tes penempatan, algoritma kami akan menyusun rangkaian modul yang spesifik untuk Anda. Anda akan dibimbing langkah demi langkah, dari konsep fundamental hingga implementasi kompleks.
                            </div>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="border-b border-gray-200 observe-animate opacity-0" data-animate="animate-fade-in-up delay-300">
                        <button @click="active === 3 ? active = null : active = 3" class="w-full flex items-center justify-between py-6 text-left group hover:bg-gray-50/50 transition-colors">
                            <span class="font-bold text-lg pr-8 text-black group-hover:text-accent transition-colors">Apa saja aktivitas dalam Proses Belajar?</span>
                            <span class="flex-shrink-0 ml-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-black text-white transition-colors group-hover:bg-accent">
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-45': active === 3}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            </span>
                        </button>
                        <div x-show="active === 3" x-collapse x-cloak>
                            <div class="pb-6 text-text-muted leading-relaxed">
                                Pembelajaran terdiri dari studi materi interaktif dan kuis evaluasi di setiap pelajaran. Anda wajib memenuhi standar nilai minimum pada kuis untuk membuka pelajaran berikutnya.
                            </div>
                        </div>
                    </div>

                    <!-- Q4 -->
                    <div class="border-b border-gray-200 observe-animate opacity-0" data-animate="animate-fade-in-up delay-400">
                        <button @click="active === 4 ? active = null : active = 4" class="w-full flex items-center justify-between py-6 text-left group hover:bg-gray-50/50 transition-colors">
                            <span class="font-bold text-lg pr-8 text-black group-hover:text-accent transition-colors">Bagaimana cara mendapatkan Sertifikat?</span>
                            <span class="flex-shrink-0 ml-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-black text-white transition-colors group-hover:bg-accent">
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-45': active === 4}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            </span>
                        </button>
                        <div x-show="active === 4" x-collapse x-cloak>
                            <div class="pb-6 text-text-muted leading-relaxed">
                                Sertifikat kompetensi diberikan setelah Anda menyelesaikan seluruh materi dan berhasil mengerjakan Proyek Akhir. Proyek ini akan divalidasi langsung oleh dosen pengampu sebagai bukti penguasaan skill.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="py-16 bg-surface border-t border-border">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12">
                <!-- Brand -->
                <div class="md:col-span-2 observe-animate opacity-0">
                    <!-- Prodi Branding -->
                    <div class="flex flex-col items-start gap-4 mb-4">
                        <span class="text-md tracking-tight">Powered by</span>
                        <!-- Logo (Contains 'Teknologi Informasi' typography) -->
                        <img src="{{ asset('storage/assets/plogo.png') }}" class="h-25 w-auto object-contain mix-blend-multiply" alt="Prodi TI Logo">
                    </div>
                    <p class="text-text-muted max-w-sm leading-relaxed mb-6">
                        Platform pembelajaran berbasis proyek untuk mahasiswa Teknologi Informasi UIN Ar-Raniry Banda Aceh.
                    </p>
                    <div class="text-xs text-text-muted uppercase tracking-widest">
                        © 2026 TechALearn. All rights reserved.
                    </div>
                </div>

                <!-- Links -->
                <div class="observe-animate opacity-0" data-animate="animate-fade-in-up delay-100">
                    <h4 class="font-bold text-sm uppercase tracking-wide mb-4">Platform</h4>
                    <ul class="space-y-3 text-text-muted">
                        <li><a href="#features" class="hover:text-accent transition-colors">Fitur</a></li>
                        <li><a href="#about" class="hover:text-accent transition-colors">Tentang</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-accent transition-colors">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-accent transition-colors">Daftar</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="observe-animate opacity-0" data-animate="animate-fade-in-up delay-200">
                    <h4 class="font-bold text-sm uppercase tracking-wide mb-4">Kontak</h4>
                    <ul class="space-y-3 text-text-muted">
                        <li>
                            <a href="https://fst.ar-raniry.ac.id" target="_blank" class="hover:text-accent transition-colors">
                                fst.ar-raniry.ac.id
                            </a>
                        </li>
                        <li>
                            <a href="https://ti.ar-raniry.ac.id" target="_blank" class="hover:text-accent transition-colors">
                                ti.ar-raniry.ac.id
                            </a>
                        </li>
                        <li>Banda Aceh, Indonesia</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const animClasses = (el.dataset.animate || 'animate-fade-in-up').split(' ');
                        el.classList.remove('opacity-0');
                        el.classList.add(...animClasses);
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.observe-animate').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>

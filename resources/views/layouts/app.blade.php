<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth h-full overflow-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta -->
    <title>@yield('title') - TechALearn </title>
    @stack('meta')

    <!-- Preconnect for faster resource loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Critical CSS inline for FCP -->
    <style>
        /* Fallback font while Google Fonts loads */
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    </style>

    <!-- Fonts - async loading with swap -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>
    
    <!-- Quill CSS - Only load when needed -->
    @stack('editor-styles')

    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Quill JS - Only load when needed -->
    @stack('editor-scripts')
</head>
<body class="bg-surface font-sans text-text-main antialiased selection:bg-accent selection:text-white h-full overflow-hidden"
    x-data="{ mobileMenuOpen: false }">

    <div class="fixed inset-0 w-full flex flex-col md:flex-row overflow-hidden">
        
        @auth
        <!-- Mobile Header (Visible only on mobile) -->
        <div class="md:hidden p-4 border-b border-border bg-white flex justify-between items-center shrink-0">
            <span class="font-bold text-lg">TECHALEARN.</span>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 border border-border">
                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
        </div>

        <!-- Desktop Sidebar (Hidden on Mobile, Static on Desktop) -->
        <aside class="hidden md:flex w-72 flex-col bg-surface border-r border-border shrink-0 h-full overflow-hidden">
             <x-layout.sidebar />
        </aside>

        <!-- Mobile Sidebar Overlay (Off-canvas) -->
        <div x-show="mobileMenuOpen" 
             class="fixed inset-0 z-50 flex md:hidden" 
             x-cloak>
            <div class="fixed inset-0 bg-black/50" @click="mobileMenuOpen = false"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white h-full border-r border-border overflow-y-auto">
                <div class="p-4 flex justify-between items-center border-b border-border">
                    <span class="font-bold">MENU</span>
                    <button @click="mobileMenuOpen = false">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <x-layout.sidebar />
            </div>
        </div>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 min-w-0 bg-surface-off h-full overflow-y-auto">
            <!-- Toast -->
            <x-ui.toast />

            <!-- Content Container -->
            <div class="p-4 md:p-8 lg:p-12">
                @yield('content')
            </div>
        </main>
    </div>
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>

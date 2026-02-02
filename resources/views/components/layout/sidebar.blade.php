<div class="h-20 border-b border-border flex items-center px-8 shrink-0">
    <span class="font-bold tracking-tight text-lg">TECHALEARN.</span>
</div>

<div class="flex-1 overflow-y-auto py-8 px-2 space-y-1">
    
    <!-- Navigation Links -->
    <nav class="px-2 space-y-2">
        
        <!-- Dashboard (Common) -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-black text-white' : 'text-text-muted hover:bg-gray-100 hover:text-black' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Dashboard
        </a>

        @if(auth()->user()->hasRole('admin'))
            <!-- ADMIN MENU -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Admin Control</p>
            </div>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-text-muted hover:bg-gray-100 hover:text-black">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Kelola Pengguna
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-text-muted hover:bg-gray-100 hover:text-black">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                Pengaturan Sistem
            </a>

            @elseif(auth()->user()->hasRole('lecturer'))
            <!-- LECTURER MENU -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Akademik</p>
            </div>

            <a href="{{ route('lecturer.modules.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium {{ request()->routeIs('lecturer.modules.*') ? 'bg-black text-white' : 'text-text-muted hover:bg-gray-100 hover:text-black' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                Kelola Modul
            </a>
            <a href="{{ route('lecturer.students.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium {{ request()->routeIs('lecturer.students.index') ? 'bg-black text-white' : 'text-text-muted hover:bg-gray-100 hover:text-black' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Daftar Siswa
            </a>
            <a href="{{ route('lecturer.bank-soal.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium {{ request()->routeIs('lecturer.bank-soal.*') ? 'bg-black text-white' : 'text-text-muted hover:bg-gray-100 hover:text-black' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Bank Soal
            </a>

        @else
            <!-- STUDENT MENU -->
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Pembelajaran</p>
            </div>

            <a href="{{ route('student.modules.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium {{ request()->routeIs('student.modules.*') ? 'bg-black text-white' : 'text-text-muted hover:bg-gray-100 hover:text-black' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                Daftar Modul
            </a>
            
            @if(!auth()->user()->isMaxLevel())
                <a href="{{ route('assessment.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium {{ request()->routeIs('assessment.*') ? 'bg-black text-white' : 'text-text-muted hover:bg-gray-100 hover:text-black' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Asesmen Level Up
                </a>
            @endif
        @endif

        <!-- Panduan Card (Common) -->
        <div class="pt-8">
            <div class="bg-black p-4 text-white relative overflow-hidden group">
                <!-- Decoration -->
                <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8 pointer-events-none"></div>
                
                <h3 class="font-bold text-sm mb-1 relative z-10">Butuh Bantuan?</h3>
                <p class="text-xs text-gray-400 mb-4 relative z-10 leading-relaxed">
                    Pelajari cara memaksimalkan fitur platform ini.
                </p>
                <a href="{{ route('panduan') }}" class="block w-full py-2.5 bg-white text-black text-center text-xs font-bold uppercase tracking-wider hover:bg-gray-200 transition-colors relative z-10">
                    Buka Panduan
                </a>
            </div>
        </div>

    </nav>
</div>

<div class="p-4 border-t border-border" x-data="{ open: false }">
    <div class="relative">
        <button @click="open = !open" class="flex items-center gap-3 w-full p-2 rounded-lg hover:bg-gray-100 transition-colors text-left group">
            <div class="w-10 h-10 bg-black text-white flex items-center justify-center font-bold text-sm tracking-wide">
                {{ substr(auth()->user()->name ?? 'User', 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-text-main truncate group-hover:text-black">{{ auth()->user()->name ?? 'Guest' }}</p>
                <p class="text-xs text-text-muted truncate">{{ auth()->user()->roles->first()->name ?? 'Student' }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
             class="absolute bottom-full left-0 w-full mb-2 bg-white border border-border shadow-lg rounded-lg overflow-hidden z-20">
             
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('settings.edit') }}" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-text-main hover:bg-gray-50 transition-colors border-b border-border">
                   <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                   Pengaturan
                </a>
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </div>
</div>

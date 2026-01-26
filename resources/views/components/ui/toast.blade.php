@props(['timeout' => 3000])

<div x-data="{ 
        notifications: [],
        add(message, type = 'success') {
            const id = Date.now();
            this.notifications.push({ id, message, type });
            setTimeout(() => this.remove(id), {{ $timeout }});
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }"
    @notify.window="add($event.detail.message, $event.detail.type)"
    class="fixed top-6 right-6 z-50 flex flex-col gap-2 pointer-events-none">

    <!-- Success Flash from Session -->
    @if(session('success'))
        <div x-init="add('{{ session('success') }}', 'success')"></div>
    @endif

    <!-- Error Flash from Session -->
    @if(session('error'))
        <div x-init="add('{{ session('error') }}', 'error')"></div>
    @endif
    
    <!-- Validation Errors (Global) -->
    @if($errors->any())
        <div x-init="add('Harap periksa kembali isian formulir Anda.', 'error')"></div>
    @endif

    <template x-for="note in notifications" :key="note.id">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             class="pointer-events-auto min-w-[320px] max-w-sm w-full bg-white border-l-4 shadow-xl p-4 flex items-start gap-3"
             :class="{
                'border-green-500': note.type === 'success',
                'border-red-500': note.type === 'error'
             }">
            
            <!-- Icon -->
            <div class="shrink-0 mt-0.5">
                <template x-if="note.type === 'success'">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="note.type === 'error'">
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-900" x-text="note.type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan'"></p>
                <p class="text-sm text-gray-600 mt-1" x-text="note.message"></p>
            </div>

            <!-- Close -->
            <button @click="remove(note.id)" class="text-gray-400 hover:text-gray-900">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>

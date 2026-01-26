@props(['id' => 'modal', 'show' => false])

<div x-show="{{ $show }}" class="fixed inset-0 z-50 flex items-center justify-center p-4 px-4 pb-20 text-center sm:block sm:p-0" x-cloak>
    <div x-show="{{ $show }}" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 transition-opacity bg-black bg-opacity-75" 
         @click="{{ $show }} = false"></div>

    <div x-show="{{ $show }}"
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="inline-block align-bottom bg-white border border-border text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            {{ $slot }}
        </div>
    </div>
</div>

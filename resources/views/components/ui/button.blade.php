@props(['variant' => 'primary'])

@php
$classes = match($variant) {
    'primary' => 'bg-black text-white hover:bg-accent',
    'secondary' => 'bg-white border border-black text-black hover:bg-black hover:text-white',
    'outline' => 'bg-transparent border-b border-gray-300 hover:border-black',
};
@endphp

<button {{ $attributes->merge(['class' => "w-full py-4 font-medium text-sm uppercase tracking-widest transition-colors duration-200 $classes"]) }}>
    {{ $slot }}
</button>

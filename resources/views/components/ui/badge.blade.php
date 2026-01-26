@props(['color' => 'accent'])

@php
$classes = match($color) {
    'accent' => 'bg-accent text-white',
    'black' => 'bg-black text-white',
    'gray' => 'bg-gray-100 text-text-muted',
    'green' => 'bg-green-500 text-white',
};
@endphp

<span {{ $attributes->merge(['class' => "text-xs px-2 py-1 font-bold uppercase tracking-wider $classes"]) }}>
    {{ $slot }}
</span>

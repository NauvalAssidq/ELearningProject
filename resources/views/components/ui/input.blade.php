@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full border-b border-gray-300 py-3 text-text-main placeholder-gray-400 focus:outline-none focus:border-black transition-colors rounded-none']) !!}>

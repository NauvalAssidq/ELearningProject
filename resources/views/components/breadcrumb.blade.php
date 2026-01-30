@props(['items' => []])

<div class="flex items-center gap-2 text-sm text-text-muted flex-wrap mb-4">
    @foreach($items as $index => $item)
        @if($index > 0)
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="opacity-50 flex-shrink-0">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        @endif

        @if($loop->last)
            <span class="font-medium text-black truncate max-w-[200px]">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] }}" class="hover:text-black transition-colors {{ isset($item['truncate']) && $item['truncate'] ? 'truncate max-w-[150px]' : '' }}">
                {{ $item['label'] }}
            </a>
        @endif
    @endforeach
</div>

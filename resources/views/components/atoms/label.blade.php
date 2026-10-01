{{-- 
    Label atómico
    @param string $size - xs, sm, md (default: sm)
--}}
<label 
    class="
        block font-sans font-medium text-gray-700 mb-xs
        {{ $size === 'xs' ? 'text-xs' : '' }}
        {{ $size === 'md' ? 'text-base' : '' }}
        {{ !$size || $size === 'sm' ? 'text-sm' : '' }}
    "
    {{ $attributes }}
>
    {{ $slot }}
</label>

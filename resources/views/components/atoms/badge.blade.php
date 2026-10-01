{{-- 
    Badge atómico
    @param string $variant - default, primary, danger (default: default)
    @param string $size - xs, sm, md (default: sm)
--}}
<span class="
    inline-flex items-center rounded-base px-sm py-xs font-sans font-medium
    {{ $variant === 'primary' ? 'bg-gray-700 text-white' : '' }}
    {{ $variant === 'danger' ? 'bg-red-100 text-red-700' : '' }}
    {{ !$variant || $variant === 'default' ? 'bg-gray-200 text-gray-700' : '' }}
    {{ $size === 'xs' ? 'text-xs' : '' }}
    {{ $size === 'md' ? 'text-base' : '' }}
    {{ !$size || $size === 'sm' ? 'text-sm' : '' }}
">
    {{ $slot }}
</span>

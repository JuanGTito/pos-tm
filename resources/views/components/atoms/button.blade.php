{{-- 
    Botón atómico
    @param string $variant - primary, secondary, danger (default: primary)
    @param string $size - xs, sm, md, lg (default: md)
    @param string $type - button, submit, reset (default: button)
--}}
<button 
    type="{{ $type ?? 'button' }}"
    class="
        px-md py-sm rounded-base border font-sans font-medium whitespace-nowrap
        transition-colors duration-150 ease-in-out
        focus:outline-none focus:ring-2 focus:ring-gray-500
        {{ $variant === 'danger' ? 'bg-red-100 border-red-300 text-red-700 hover:bg-red-200' : '' }}
        {{ $variant === 'secondary' ? 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200' : '' }}
        {{ !$variant || $variant === 'primary' ? 'bg-gray-700 border-gray-800 text-white hover:bg-gray-800' : '' }}
        {{ $size === 'sm' ? 'text-xs px-sm py-xs' : '' }}
        {{ $size === 'lg' ? 'text-lg px-lg py-md' : '' }}
        {{ !$size || $size === 'md' ? 'text-sm px-md py-sm' : '' }}
        disabled:opacity-50 disabled:cursor-not-allowed
    "
    {{ $attributes }}
>
    {{ $slot }}
</button>

{{-- 
    Input atómico
    @param string $size - xs, sm, md, lg (default: md)
    @param string $type - text, email, password, etc (default: text)
--}}
<input 
    type="{{ $type ?? 'text' }}"
    class="
        w-full px-md py-sm rounded-base border border-gray-300 
        bg-white text-gray-800 font-sans
        placeholder:text-gray-400
        focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent
        disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-500
        {{ $size === 'sm' ? 'text-sm px-sm py-xs' : '' }}
        {{ $size === 'lg' ? 'text-lg px-lg py-md' : '' }}
        {{ !$size || $size === 'md' ? 'text-base' : '' }}
    "
    {{ $attributes }}
/>

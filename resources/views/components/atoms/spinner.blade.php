{{-- 
    Spinner atómico de carga
    @param string $size - sm, md, lg (default: md)
--}}
<div class="
    inline-block animate-spin
    {{ $size === 'sm' ? 'w-4 h-4' : '' }}
    {{ $size === 'lg' ? 'w-8 h-8' : '' }}
    {{ !$size || $size === 'md' ? 'w-6 h-6' : '' }}
">
    <svg class="w-full h-full border-2 border-gray-300 border-t-gray-700 rounded-full" fill="none" viewBox="0 0 24 24"></svg>
</div>

{{-- 
    Texto atómico
    @param string $size - xs, sm, base, md, lg, xl, 2xl (default: base)
    @param string $weight - normal, medium, semibold, bold (default: normal)
    @param string $color - primary, secondary, muted, danger (default: primary)
--}}
<p class="
    font-sans
    {{ $weight === 'medium' ? 'font-medium' : '' }}
    {{ $weight === 'semibold' ? 'font-semibold' : '' }}
    {{ $weight === 'bold' ? 'font-bold' : '' }}
    {{ !$weight || $weight === 'normal' ? 'font-normal' : '' }}
    {{ $size === 'xs' ? 'text-xs' : '' }}
    {{ $size === 'sm' ? 'text-sm' : '' }}
    {{ $size === 'md' ? 'text-md' : '' }}
    {{ $size === 'lg' ? 'text-lg' : '' }}
    {{ $size === 'xl' ? 'text-xl' : '' }}
    {{ $size === '2xl' ? 'text-2xl' : '' }}
    {{ !$size || $size === 'base' ? 'text-base' : '' }}
    {{ $color === 'secondary' ? 'text-gray-600' : '' }}
    {{ $color === 'muted' ? 'text-gray-400' : '' }}
    {{ $color === 'danger' ? 'text-red-600' : '' }}
    {{ !$color || $color === 'primary' ? 'text-gray-800' : '' }}
">
    {{ $slot }}
</p>

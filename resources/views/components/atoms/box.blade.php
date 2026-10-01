{{-- 
    Box atómico simple - Base para todas las cajas
    @param string $padding - xs, sm, md, lg, xl (default: md)
    @param string $border - none, xs, sm, md, lg (default: sm)
    @param string $rounded - none, xs, sm, base, md, lg, xl (default: base)
--}}
<div class="bg-gray-50 border border-gray-300 {{ 'p-' . ($padding ?? 'md') }} {{ 'rounded-' . ($rounded ?? 'base') }}">
    {{ $slot }}
</div>

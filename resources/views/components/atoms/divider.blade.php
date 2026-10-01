{{-- 
    Divisor atómico
    @param string $spacing - xs, sm, md, lg, xl (default: md)
--}}
<div class="
    border-t border-gray-300
    {{ $spacing === 'xs' ? 'my-xs' : '' }}
    {{ $spacing === 'sm' ? 'my-sm' : '' }}
    {{ $spacing === 'lg' ? 'my-lg' : '' }}
    {{ $spacing === 'xl' ? 'my-xl' : '' }}
    {{ !$spacing || $spacing === 'md' ? 'my-md' : '' }}
"></div>

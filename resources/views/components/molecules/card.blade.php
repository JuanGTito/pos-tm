{{-- 
    Card - Contenedor con espaciado optimizado
    @param string $padding - xs, sm, md, lg, xl (default: md)
    @param string $title - Título opcional del card
--}}
<x-atoms.box padding="{{ $padding ?? 'md' }}" rounded="md">
    @if($title ?? null)
        <div class="mb-sm">
            <h3 class="text-base font-semibold text-gray-800">{{ $title }}</h3>
        </div>
        <x-atoms.divider spacing="sm" />
    @endif
    {{ $slot }}
</x-atoms.box>

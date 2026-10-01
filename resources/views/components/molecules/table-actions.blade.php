{{-- 
    Acción de tabla - Botones pequeños para acciones
    @param string $editRoute - Ruta para editar
    @param string $deleteRoute - Ruta para eliminar
    @param string $viewRoute - Ruta para ver
--}}
<div class="flex gap-xs">
    @if($viewRoute ?? null)
        <x-atoms.button variant="secondary" size="sm" onclick="window.location.href='{{ $viewRoute }}'">
            Ver
        </x-atoms.button>
    @endif
    @if($editRoute ?? null)
        <x-atoms.button variant="secondary" size="sm" onclick="window.location.href='{{ $editRoute }}'">
            Editar
        </x-atoms.button>
    @endif
    @if($deleteRoute ?? null)
        <x-atoms.button variant="danger" size="sm" onclick="if(confirm('¿Estás seguro?')) window.location.href='{{ $deleteRoute }}'">
            Eliminar
        </x-atoms.button>
    @endif
</div>

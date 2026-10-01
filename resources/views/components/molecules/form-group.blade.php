{{-- 
    Grupo de formulario - Composición de label + input compacta
    @param string $label - Texto del label
    @param string $inputType - Tipo de input (default: text)
    @param string $inputSize - Tamaño del input
--}}
<div class="flex flex-col gap-xs">
    <x-atoms.label>
        {{ $label ?? '' }}
    </x-atoms.label>
    <x-atoms.input 
        type="{{ $inputType ?? 'text' }}"
        size="{{ $inputSize ?? 'md' }}"
        {{ $attributes }}
    />
</div>

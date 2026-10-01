{{-- 
    Alerta atómica - Compacta y limpia
    @param string $type - info, success, warning, danger (default: info)
    @param string $title - Título de la alerta
    @param boolean $closeable - Permitir cerrar (default: true)
--}}
<div class="
    px-md py-sm rounded-base border
    {{ $type === 'success' ? 'bg-green-50 border-green-300 text-green-800' : '' }}
    {{ $type === 'warning' ? 'bg-yellow-50 border-yellow-300 text-yellow-800' : '' }}
    {{ $type === 'danger' ? 'bg-red-50 border-red-300 text-red-800' : '' }}
    {{ !$type || $type === 'info' ? 'bg-blue-50 border-blue-300 text-blue-800' : '' }}
    flex justify-between items-start gap-md
" role="alert" data-alert="{{ $type ?? 'info' }}">
    <div class="text-sm">
        @if($title ?? null)
            <h4 class="font-semibold mb-xs">{{ $title }}</h4>
        @endif
        <p>{{ $slot }}</p>
    </div>
    @if($closeable !== false)
        <button onclick="this.closest('[role=alert]').remove()" class="text-xl leading-none opacity-70 hover:opacity-100 flex-shrink-0">
            ✕
        </button>
    @endif
</div>

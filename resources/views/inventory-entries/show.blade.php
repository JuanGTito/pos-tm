<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div><h2 class="text-xl font-semibold text-gray-800">Ingreso de mercadería #{{ $inventoryEntry->id }}</h2><p class="text-sm text-gray-500">Detalle de productos, costos y lotes.</p></div>
            <a href="{{ route('inventory-entries.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Volver</a>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))<div class="mb-4 rounded-lg border border-green-300 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-red-700">{{ session('error') }}</div>@endif

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-xl border bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase text-gray-500">Fecha</p><p class="mt-1 text-lg font-bold">{{ ($inventoryEntry->purchase_date ?? $inventoryEntry->created_at)->format('d/m/Y') }}</p></div>
            <div class="rounded-xl border bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase text-gray-500">Origen</p><p class="mt-1 font-bold {{ $inventoryEntry->funding_source === 'sales_revenue' ? 'text-blue-700' : 'text-emerald-700' }}">{{ $inventoryEntry->funding_source_label }}</p></div>
            <div class="rounded-xl border bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase text-gray-500">Proveedor</p><p class="mt-1 font-bold">{{ $inventoryEntry->supplier ?: 'No indicado' }}</p><p class="text-xs text-gray-500">{{ $inventoryEntry->reference }}</p></div>
            <div class="rounded-xl border bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase text-gray-500">Costo total</p><p class="mt-1 text-2xl font-black text-indigo-700">S/. {{ number_format($inventoryEntry->total_cost, 2) }}</p></div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-bold">Productos ingresados</h3><span class="text-sm text-gray-500">{{ $inventoryEntry->total_items_quantity }} unidades</span></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-left text-xs font-bold uppercase text-gray-500"><tr><th class="px-4 py-3">Código</th><th class="px-4 py-3">Producto</th><th class="px-4 py-3">Lote</th><th class="px-4 py-3 text-right">Cantidad</th><th class="px-4 py-3 text-right">Costo unit.</th><th class="px-4 py-3 text-right">Precio venta</th><th class="px-4 py-3 text-right">Subtotal</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($inventoryEntry->items as $item)
                            <tr><td class="px-4 py-3 text-sm font-bold text-indigo-700">{{ $item->product_code }}</td><td class="px-4 py-3 text-sm font-medium">{{ $item->product_name }}</td><td class="px-4 py-3 text-sm">{{ $item->lot_number ?: '—' }}</td><td class="px-4 py-3 text-right text-sm font-bold">{{ $item->quantity }}</td><td class="px-4 py-3 text-right text-sm">S/. {{ number_format($item->unit_cost, 2) }}</td><td class="px-4 py-3 text-right text-sm text-emerald-700">S/. {{ number_format($item->sale_price, 2) }}</td><td class="px-4 py-3 text-right text-sm font-bold">S/. {{ number_format($item->subtotal, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Este registro antiguo no tiene detalle por producto.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($inventoryEntry->notes || $inventoryEntry->photo_path)
                <div class="mt-6 grid gap-4 border-t pt-5 md:grid-cols-2">
                    <div><p class="text-xs font-bold uppercase text-gray-500">Notas</p><p class="mt-1 text-sm text-gray-700">{{ $inventoryEntry->notes ?: 'Sin observaciones' }}</p></div>
                    @if($inventoryEntry->photo_path)<div><p class="mb-2 text-xs font-bold uppercase text-gray-500">Comprobante</p><a href="{{ asset('storage/'.$inventoryEntry->photo_path) }}" target="_blank"><img src="{{ asset('storage/'.$inventoryEntry->photo_path) }}" alt="Comprobante del ingreso" class="max-h-56 rounded-lg border object-contain"></a></div>@endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Historial de ingresos</h2>
                <p class="text-sm text-gray-500">Compras, inversiones, lotes y reposiciones de inventario.</p>
            </div>
            <a href="{{ route('products.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 font-semibold text-white hover:bg-indigo-700">+ Ingresar mercadería</a>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))<div class="mb-4 rounded-lg border border-green-300 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-red-700">{{ session('error') }}</div>@endif

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="GET" class="mb-5 flex flex-wrap items-end gap-3">
                <div>
                    <label for="source" class="block text-xs font-bold uppercase text-gray-500">Origen</label>
                    <select id="source" name="source" class="mt-1 rounded-lg border-gray-300">
                        <option value="">Todos</option>
                        <option value="new_investment" @selected($source === 'new_investment')>Inversión externa</option>
                        <option value="sales_revenue" @selected($source === 'sales_revenue')>Fondo de ventas</option>
                    </select>
                </div>
                <button class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-200 hover:bg-blue-700">Filtrar</button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-left text-xs font-bold uppercase text-gray-500">
                        <tr><th class="px-4 py-3">Ingreso</th><th class="px-4 py-3">Fecha</th><th class="px-4 py-3">Proveedor / referencia</th><th class="px-4 py-3">Origen</th><th class="px-4 py-3">Productos</th><th class="px-4 py-3 text-right">Costo</th><th class="px-4 py-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($entries as $entry)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-bold text-indigo-700">#{{ $entry->id }}</td>
                                <td class="px-4 py-3 text-sm">{{ ($entry->purchase_date ?? $entry->created_at)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm"><p class="font-medium">{{ $entry->supplier ?: 'Sin proveedor' }}</p><p class="text-xs text-gray-500">{{ $entry->reference ?: 'Sin referencia' }}</p></td>
                                <td class="px-4 py-3"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $entry->funding_source === 'sales_revenue' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $entry->funding_source_label }}</span></td>
                                <td class="px-4 py-3 text-sm">{{ $entry->items_count }} tipos / {{ $entry->total_items_quantity }} unidades</td>
                                <td class="px-4 py-3 text-right font-bold">S/. {{ number_format($entry->total_cost, 2) }}</td>
                                <td class="px-4 py-3 text-right"><a href="{{ route('inventory-entries.show', $entry) }}" class="font-semibold text-indigo-600 hover:text-indigo-900">Ver</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">Todavía no hay ingresos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $entries->links() }}</div>
        </div>
    </div>
</x-app-layout>

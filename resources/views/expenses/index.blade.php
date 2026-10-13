<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div><h2 class="text-xl font-semibold text-gray-800">Gastos</h2><p class="text-sm text-gray-500">Egresos del negocio y compras pagadas con el fondo de ventas.</p></div>
            <a href="{{ route('expenses.create') }}" class="rounded-lg bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">+ Registrar gasto</a>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))<div class="mb-4 rounded-lg border border-green-300 bg-green-50 p-4 text-green-700">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-red-700">{{ session('error') }}</div>@endif
        <div class="mb-5 grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border-l-4 border-blue-500 bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase text-gray-500">Fondo de ventas disponible</p><p class="mt-1 text-2xl font-black text-blue-700">S/. {{ number_format($availableSalesBalance, 2) }}</p></div>
            <div class="rounded-xl border-l-4 border-red-500 bg-white p-5 shadow-sm"><p class="text-xs font-bold uppercase text-gray-500">Todos los gastos registrados</p><p class="mt-1 text-2xl font-black text-red-700">S/. {{ number_format($totalExpenses, 2) }}</p></div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="GET" class="mb-5 flex items-end gap-3"><div><label for="category" class="block text-sm font-semibold text-slate-700">Categoría</label><select id="category" name="category" class="mt-1 rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"><option value="">Todas</option>@foreach($categories as $code => $label)<option value="{{ $code }}" @selected($category === $code)>{{ $label }}</option>@endforeach</select></div><button class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-200 hover:bg-blue-700">Filtrar</button></form>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-left text-xs font-bold uppercase text-gray-500"><tr><th class="px-4 py-3">Fecha</th><th class="px-4 py-3">Detalle</th><th class="px-4 py-3">Categoría</th><th class="px-4 py-3">Origen</th><th class="px-4 py-3">Comprobante</th><th class="px-4 py-3 text-right">Monto</th><th class="px-4 py-3"></th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $expense->expense_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm"><p class="font-semibold">{{ $expense->description }}</p>@if($expense->notes)<p class="max-w-xs truncate text-xs text-gray-500">{{ $expense->notes }}</p>@endif</td>
                                <td class="px-4 py-3 text-sm">{{ $expense->category_label }}</td>
                                <td class="px-4 py-3"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $expense->payment_source === 'sales_revenue' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $expense->payment_source_label }}</span></td>
                                <td class="px-4 py-3 text-sm">@if($expense->photo_path)<a href="{{ asset('storage/'.$expense->photo_path) }}" target="_blank" class="font-semibold text-indigo-600">Ver foto</a>@else<span class="text-gray-400">—</span>@endif</td>
                                <td class="px-4 py-3 text-right font-bold text-red-700">S/. {{ number_format($expense->amount, 2) }}</td>
                                <td class="px-4 py-3 text-right text-sm">
                                    @if($expense->productPurchase)<a href="{{ route('inventory-entries.show', $expense->productPurchase) }}" class="font-semibold text-indigo-600">Ver ingreso</a>
                                    @else<a href="{{ route('expenses.edit', $expense) }}" class="mr-3 font-semibold text-indigo-600">Editar</a><form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este gasto? El saldo se recalculará.');">@csrf @method('DELETE')<button class="font-semibold text-red-600">Eliminar</button></form>@endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">No hay gastos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $expenses->links() }}</div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Venta #' . $sale->id) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="border-l-4 border-indigo-500 pl-4">
                            <p class="text-gray-600 text-sm uppercase">ID Venta</p>
                            <p class="text-2xl font-bold text-gray-900">#{{ $sale->id }}</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="text-gray-600 text-sm uppercase">Fecha</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $sale->sale_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="text-gray-600 text-sm uppercase">Cliente</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $sale->customer->name ?? 'N/A' }}</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="text-gray-600 text-sm uppercase">Estado</p>
                            <p class="text-lg font-semibold text-green-600">{{ ucfirst($sale->status) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles de Productos</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                            <thead class="bg-gray-100 border-b border-gray-300">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Código</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Cantidad</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Precio Unit.</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($details as $detail)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $detail->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->product->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700 font-semibold">{{ $detail->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">S/. {{ number_format($detail->price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 font-bold">S/. {{ number_format($detail->subtotal, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No hay detalles registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-gray-600 text-sm uppercase">Subtotal</p>
                            <p class="text-3xl font-bold text-gray-900">S/. {{ number_format($sale->total - $sale->tax, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-gray-600 text-sm uppercase">Impuesto (19%)</p>
                            <p class="text-3xl font-bold text-gray-900">S/. {{ number_format($sale->tax, 2) }}</p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                            <p class="text-gray-600 text-sm uppercase">Total</p>
                            <p class="text-3xl font-bold text-indigo-600">S/. {{ number_format($sale->total, 2) }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        @can('update', $sale)
                            <a href="{{ route('sales.edit', $sale->id) }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Editar
                            </a>
                        @endcan
                        <button onclick="printSale()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Imprimir
                        </button>
                        @can('delete', $sale)
                            <button onclick="deleteSale('{{ $sale->id }}')" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Eliminar
                            </button>
                        @endcan
                        <a href="{{ route('sales.index') }}" class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition-colors">
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printSale() {
            window.print();
        }

        function deleteSale(saleId) {
            if (confirm('¿Está seguro de que desea eliminar esta venta? El stock se restaurará automáticamente.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sales/${saleId}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>

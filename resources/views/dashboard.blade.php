<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Resumen Financiero y Flujo de Capital -->
            <div class="mb-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Balance Financiero y Capital</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-l-4 border-indigo-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ingresos por Ventas</p>
                        <p class="text-2xl font-black text-gray-900 mt-1">S/. {{ number_format(\App\Models\Sale::sum('total'), 2) }}</p>
                        <p class="text-[11px] text-gray-400 mt-1">Precio final cobrado acumulado</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-l-4 border-blue-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disponible de Ventas</p>
                        <p class="text-2xl font-black text-blue-600 mt-1">S/. {{ number_format(\App\Models\ProductPurchase::availableSalesBalance(), 2) }}</p>
                        <p class="text-[11px] text-blue-500 font-medium mt-1">Saldo libre para alzar/compras</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-l-4 border-amber-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reinvertido de Ventas</p>
                        <p class="text-2xl font-black text-amber-600 mt-1">S/. {{ number_format(\App\Models\ProductPurchase::totalReinvestedFromSales(), 2) }}</p>
                        <p class="text-[11px] text-gray-400 mt-1">Alzado de ventas para mercadería</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border-l-4 border-emerald-500">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nueva Inversión</p>
                        <p class="text-2xl font-black text-emerald-600 mt-1">S/. {{ number_format(\App\Models\ProductPurchase::totalNewInvestment(), 2) }}</p>
                        <p class="text-[11px] text-gray-400 mt-1">Capital aportado externo</p>
                    </div>
                </div>
            </div>

            <!-- Resumen de Inventario -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total de Productos</h3>
                        <p class="text-3xl font-bold text-indigo-600">{{ \App\Models\Product::count() }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Stock Total</h3>
                        <p class="text-3xl font-bold text-green-600">{{ \App\Models\Product::sum('stock') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Stock Bajo</h3>
                        <p class="text-3xl font-bold text-red-600">{{ \App\Models\Product::whereRaw('stock <= min_stock')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Acciones Principales</h3>
                        <a href="{{ route('sales.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Ver todas las ventas →</a>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('sales.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-semibold shadow-md">
                            <span class="mr-2">💳</span> + Nueva Venta
                        </a>
                        <a href="{{ route('products.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors font-semibold shadow-md">
                            <span class="mr-2">📦</span> + Ingresar Mercadería / Inversión
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabla de Productos Recientes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Productos Recientes</h3>
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Ver todos</a>
                    </div>

                    <!-- Buscador -->
                    <div class="mb-6">
                        <div class="flex gap-3">
                            <input 
                                type="text" 
                                id="dashboardSearchInput" 
                                placeholder="Buscar productos..." 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                            <button id="dashboardSearchBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Tabla -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                            <thead class="bg-gray-100 border-b border-gray-300">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Precio Venta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="dashboardProductsTable">
                                @php
                                    $recentProducts = \App\Models\Product::orderBy('id', 'desc')->limit(10)->get();
                                @endphp
                                @foreach($recentProducts as $product)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">${{ number_format($product->sale_price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full @if($product->stock > $product->min_stock) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($product->stock > $product->min_stock)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Disponible
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Stock Bajo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para búsqueda en dashboard -->
    <script>
        document.getElementById('dashboardSearchBtn').addEventListener('click', function() {
            performDashboardSearch();
        });

        document.getElementById('dashboardSearchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performDashboardSearch();
            }
        });

        function performDashboardSearch() {
            const search = document.getElementById('dashboardSearchInput').value;
            
            fetch(`{{ route('products.index') }}?search=${encodeURIComponent(search)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            })
            .then(response => response.text())
            .then(html => {
                // Extrae el contenido de la tabla del HTML devuelto
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const tableBody = doc.querySelector('tbody');
                document.querySelector('#dashboardProductsTable').innerHTML = tableBody ? tableBody.innerHTML : '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron productos.</td></tr>';
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-app-layout>

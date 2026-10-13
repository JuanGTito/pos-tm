<div class="overflow-x-auto mt-6">
    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
        <thead class="bg-gray-100 border-b border-gray-300">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Código</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Descripción</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Precio Compra</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Precio Venta</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Stock Mín.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->category_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <span class="truncate max-w-xs inline-block">{{ Str::limit($product->description, 30) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">S/. {{ number_format($product->purchase_price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">S/. {{ number_format($product->sale_price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full @if($product->stock > $product->min_stock) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $product->min_stock }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @can('update', $product)
                            <button onclick="editProduct('{{ $product->id }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                            <button onclick="deleteProduct('{{ $product->id }}')" class="text-red-600 hover:text-red-900">Eliminar</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                        No se encontraron productos.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->total() > 0)
    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endif

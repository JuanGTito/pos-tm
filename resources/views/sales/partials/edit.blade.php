<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Venta #' . $sale->id) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sales.update', $sale->id) }}" method="POST" id="formSale">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Cliente</h3>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Cliente</label>
                            <select name="customer_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" @if($sale->customer_id === $customer->id) selected @endif>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos de la Venta</h3>

                        <div class="mb-4">
                            <div id="productosContainer">
                                @foreach($details as $index => $detail)
                                    <div class="producto-item border border-gray-200 p-4 rounded-lg mb-4" data-item="{{ $index }}">
                                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-3">
                                            <div>
                                                <label class="block text-gray-700 text-sm font-semibold mb-2">Producto</label>
                                                <select name="items[{{ $index }}][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 producto-select" required onchange="actualizarPrecio(this)">
                                                    <option value="">-- Selecciona --</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-precio="{{ $product->sale_price }}" data-stock="{{ $product->stock }}" @if($product->id === $detail->product_id) selected @endif>
                                                            {{ $product->code }} - {{ $product->name }} (Stock: {{ $product->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-sm font-semibold mb-2">Cantidad</label>
                                                <input type="number" name="items[{{ $index }}][quantity]" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cantidad-input" value="{{ $detail->quantity }}" onchange="calcularSubtotal(this)" required>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-sm font-semibold mb-2">Precio Unit.</label>
                                                <input type="number" name="items[{{ $index }}][price]" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 precio-input" value="{{ $detail->price }}" onchange="calcularSubtotal(this)" required>
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-sm font-semibold mb-2">Subtotal</label>
                                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 subtotal-display" value="${{ number_format($detail->subtotal, 2) }}" disabled>
                                                <input type="hidden" name="items[{{ $index }}][subtotal]" class="subtotal-value" value="{{ $detail->subtotal }}">
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-sm font-semibold mb-2">&nbsp;</label>
                                                <button type="button" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" onclick="eliminarProducto(this)">
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" onclick="agregarProducto()">
                            + Agregar Producto
                        </button>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-600 text-sm uppercase">Subtotal</p>
                                <p class="text-3xl font-bold text-gray-900">S/. <span id="subtotalTotal">{{ number_format($sale->total - $sale->tax, 2) }}</span></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-600 text-sm uppercase">Impuesto (19%)</p>
                                <p class="text-3xl font-bold text-gray-900">S/. <span id="impuestoTotal">{{ number_format($sale->tax, 2) }}</span></p>
                            </div>
                            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                                <p class="text-gray-600 text-sm uppercase">Total</p>
                                <p class="text-3xl font-bold text-indigo-600">S/. <span id="totalVenta">{{ number_format($sale->total, 2) }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-semibold">
                        Actualizar Venta
                    </button>
                    <a href="{{ route('sales.show', $sale->id) }}" class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition-colors font-semibold text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div id="init-data" data-item-count="{{ count($details) ?? 0 }}"></div>

    <script>
        // Initialize itemCount from data attribute
        const initData = document.getElementById('init-data');
        let itemCount = parseInt(initData?.dataset.itemCount || '0', 10);

        function actualizarPrecio(select) {
            const precio = select.options[select.selectedIndex].dataset.precio;
            const precioInput = select.closest('.producto-item').querySelector('.precio-input');
            precioInput.value = precio;
            calcularSubtotal(precioInput);
        }

        function calcularSubtotal(input) {
            const item = input.closest('.producto-item');
            const cantidad = item.querySelector('.cantidad-input').value || 0;
            const precio = item.querySelector('.precio-input').value || 0;
            const subtotal = cantidad * precio;

            item.querySelector('.subtotal-display').value = '$' + parseFloat(subtotal).toFixed(2);
            item.querySelector('.subtotal-value').value = subtotal;

            calcularTotal();
        }

        function calcularTotal() {
            let totalSubtotal = 0;

            document.querySelectorAll('.producto-item').forEach(item => {
                const subtotal = item.querySelector('.subtotal-value').value || 0;
                totalSubtotal += parseFloat(subtotal);
            });

            const total = totalSubtotal;
            const baseImponible = total / 1.19;
            const impuesto = total - baseImponible;

            document.getElementById('subtotalTotal').textContent = parseFloat(baseImponible).toFixed(2);
            document.getElementById('impuestoTotal').textContent = parseFloat(impuesto).toFixed(2);
            document.getElementById('totalVenta').textContent = parseFloat(total).toFixed(2);
        }

        function agregarProducto() {
            const container = document.getElementById('productosContainer');
            const template = document.querySelector('.producto-item');
            const nuevoItem = template.cloneNode(true);

            nuevoItem.setAttribute('data-item', itemCount);
            const inputs = nuevoItem.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/g, `[${itemCount}]`));
                }
                input.value = '';
            });

            nuevoItem.querySelector('.producto-select').onchange = function() { actualizarPrecio(this); };
            nuevoItem.querySelector('.cantidad-input').onchange = function() { calcularSubtotal(this); };
            nuevoItem.querySelector('.precio-input').onchange = function() { calcularSubtotal(this); };

            container.appendChild(nuevoItem);
            itemCount++;
        }

        function eliminarProducto(btn) {
            const items = document.querySelectorAll('.producto-item');
            if (items.length > 1) {
                btn.closest('.producto-item').remove();
                calcularTotal();
            } else {
                alert('Debe haber al menos un producto');
            }
        }

        // Calcular totales al cargar
        calcularTotal();
    </script>
</x-app-layout>

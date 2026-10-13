<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Ingreso de mercadería</h2>
                <p class="text-sm text-gray-500">Registra productos nuevos o repón existencias por lote.</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <span class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-blue-700">
                    Fondo de ventas: S/. {{ number_format($availableSalesBalance, 2) }}
                </span>
                <a href="{{ route('inventory-entries.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-gray-700 hover:bg-gray-50">
                    Ver historial
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('error'))
            <div class="mb-5 rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-5 rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-bold">Revisa los datos del ingreso:</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="purchaseForm">
            @csrf
            <div class="mb-6 grid grid-cols-1 gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm md:grid-cols-4">
                <div>
                    <label for="purchase_date" class="block text-xs font-bold uppercase text-gray-500">Fecha</label>
                    <input id="purchase_date" name="purchase_date" type="date" value="{{ old('purchase_date', now()->toDateString()) }}" required class="mt-1 w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label for="supplier" class="block text-xs font-bold uppercase text-gray-500">Proveedor</label>
                    <input id="supplier" name="supplier" value="{{ old('supplier') }}" placeholder="Opcional" class="mt-1 w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label for="reference" class="block text-xs font-bold uppercase text-gray-500">Factura / referencia</label>
                    <input id="reference" name="reference" value="{{ old('reference') }}" placeholder="Opcional" class="mt-1 w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label for="photo" class="block text-xs font-bold uppercase text-gray-500">Foto del comprobante</label>
                    <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-indigo-700">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <section class="h-fit rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 border-b pb-3 text-lg font-bold text-gray-800">Producto y lote</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Reponer producto existente</label>
                            <select id="temp_existing" class="mt-1 w-full rounded-lg border-gray-300">
                                <option value="">Producto nuevo</option>
                                @foreach($productsCatalog as $catalogProduct)
                                    <option value="{{ $catalogProduct->id }}">{{ $catalogProduct->code }} — {{ $catalogProduct->name }} (stock: {{ $catalogProduct->stock }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Al elegir uno, su costo y precio se reemplazarán con los de este ingreso.</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría</label>
                                <select id="temp_category" class="mt-1 w-full rounded-lg border-gray-300">
                                    <option value="">Selecciona</option>
                                    @foreach($categories as $code => $name)<option value="{{ $code }}">{{ $name }}</option>@endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Código / SKU</label>
                                <input id="temp_code" maxlength="20" placeholder="NET-001" class="mt-1 w-full rounded-lg border-gray-300 uppercase">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input id="temp_name" maxlength="255" placeholder="Router Wi‑Fi 6" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción / especificaciones</label>
                            <textarea id="temp_description" rows="2" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lote</label>
                            <input id="temp_lot" maxlength="100" placeholder="Opcional; se genera si lo dejas vacío" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Costo unit.</label>
                                <input id="temp_price" type="number" step="0.01" min="0" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Precio venta</label>
                                <input id="temp_sale_price" type="number" step="0.01" min="0" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unidades</label>
                                <input id="temp_stock" type="number" min="1" class="mt-1 w-full rounded-lg border-gray-300">
                            </div>
                        </div>
                        <button type="button" id="btnAddToList" class="w-full rounded-lg bg-indigo-600 py-3 font-bold text-white hover:bg-indigo-700">Agregar a la lista</button>
                    </div>
                </section>

                <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm xl:col-span-2">
                    <div class="mb-4 flex items-center justify-between border-b pb-3">
                        <h3 class="text-lg font-bold text-gray-800">Productos a ingresar</h3>
                        <span id="itemsBadge" class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">0 productos</span>
                    </div>
                    <div class="min-h-[220px] overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-left text-xs font-bold uppercase text-gray-500">
                                <tr>
                                    <th class="px-3 py-2">Producto / lote</th>
                                    <th class="px-3 py-2">Costo</th>
                                    <th class="px-3 py-2">Venta</th>
                                    <th class="px-3 py-2 text-center">Cant.</th>
                                    <th class="px-3 py-2 text-right">Subtotal</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="divide-y divide-gray-100">
                                <tr id="emptyRow"><td colspan="6" class="px-4 py-10 text-center text-sm text-gray-400">Agrega uno o varios productos.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="fundingSection" class="mt-6 hidden border-t pt-6">
                        <div class="mb-5 flex flex-col justify-between gap-2 rounded-xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center">
                            <div><p class="text-xs font-bold uppercase text-gray-500">Costo total</p><p id="totalInvestmentLabel" class="text-2xl font-black text-indigo-700">S/. 0.00</p></div>
                            <p id="totalUnitsLabel" class="font-semibold text-gray-600">0 unidades</p>
                        </div>
                        <h4 class="mb-3 text-sm font-bold uppercase text-gray-700">Origen del dinero</h4>
                        <div class="mb-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                            <label class="cursor-pointer rounded-xl border-2 border-gray-200 p-4 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                                <span class="flex items-center justify-between font-bold"><span>Nueva inversión externa</span><input type="radio" name="funding_source" value="new_investment" @checked(old('funding_source', 'new_investment') === 'new_investment')></span>
                                <span class="mt-1 block text-xs text-gray-500">No reduce el dinero acumulado por ventas.</span>
                            </label>
                            <label class="cursor-pointer rounded-xl border-2 border-gray-200 p-4 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <span class="flex items-center justify-between font-bold"><span>Reinversión del fondo de ventas</span><input type="radio" name="funding_source" value="sales_revenue" @checked(old('funding_source') === 'sales_revenue')></span>
                                <span class="mt-1 block text-xs text-gray-500">Se registra también como gasto de mercadería. Disponible: S/. {{ number_format($availableSalesBalance, 2) }}</span>
                            </label>
                        </div>
                        <div id="insufficientBalanceAlert" class="mb-4 hidden rounded-lg border border-amber-300 bg-amber-50 p-3 text-sm font-semibold text-amber-800">El costo supera el fondo de ventas disponible.</div>
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notes" rows="2" class="mt-1 w-full rounded-lg border-gray-300" placeholder="Condiciones, transporte u observaciones">{{ old('notes') }}</textarea>
                        <div class="mt-5 flex justify-end">
                            <button type="submit" id="btnSaveAll" class="rounded-xl bg-indigo-600 px-8 py-3 font-bold text-white shadow hover:bg-indigo-700">Registrar ingreso y actualizar stock</button>
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>

    <script>
        const catalog = @json($productsCatalog->keyBy('id'));
        const oldItems = @json(old('products', []));
        const availableSalesBalance = {{ (float) $availableSalesBalance }};
        const tableBody = document.getElementById('tableBody');
        const emptyRow = document.getElementById('emptyRow');
        let rowIndex = 0;

        const fields = {
            existing: document.getElementById('temp_existing'),
            category: document.getElementById('temp_category'),
            code: document.getElementById('temp_code'),
            name: document.getElementById('temp_name'),
            description: document.getElementById('temp_description'),
            lot: document.getElementById('temp_lot'),
            price: document.getElementById('temp_price'),
            salePrice: document.getElementById('temp_sale_price'),
            stock: document.getElementById('temp_stock'),
        };

        fields.existing.addEventListener('change', () => {
            const product = catalog[fields.existing.value];
            if (!product) {
                clearProductFields();
                return;
            }
            fields.category.value = product.category || product.code.substring(0, 3);
            fields.code.value = product.code;
            fields.name.value = product.name;
            fields.description.value = product.description || '';
            fields.price.value = product.purchase_price;
            fields.salePrice.value = product.sale_price;
            fields.lot.value = '';
            fields.stock.value = '';
        });

        fields.category.addEventListener('change', () => {
            if (!fields.existing.value && fields.category.value && !fields.code.value) {
                fields.code.value = `${fields.category.value}-`;
                fields.code.focus();
            }
        });

        document.getElementById('btnAddToList').addEventListener('click', () => {
            addItem({
                product_id: fields.existing.value,
                category: fields.category.value,
                code: fields.code.value.trim().toUpperCase(),
                name: fields.name.value.trim(),
                description: fields.description.value.trim(),
                lot_number: fields.lot.value.trim(),
                purchase_price: fields.price.value,
                sale_price: fields.salePrice.value,
                stock: fields.stock.value,
            }, true);
        });

        function addItem(item, alertOnError = false) {
            const purchasePrice = Number(item.purchase_price);
            const stock = Number.parseInt(item.stock, 10);
            const salePrice = Number(item.sale_price) > 0 ? Number(item.sale_price) : Number((purchasePrice * 1.20).toFixed(2));
            const identity = item.product_id ? `id:${item.product_id}` : `code:${item.code}`;

            if (!item.category || !item.code || !item.name || Number.isNaN(purchasePrice) || purchasePrice < 0 || !stock || stock < 1) {
                if (alertOnError) alert('Completa categoría, código, nombre, costo y unidades.');
                return;
            }
            if ([...tableBody.querySelectorAll('tr[data-identity]')].some(row => row.dataset.identity === identity)) {
                if (alertOnError) alert('Ese producto ya está en la lista. Elimínalo o agrupa la cantidad.');
                return;
            }

            emptyRow?.remove();
            const index = rowIndex++;
            const subtotal = purchasePrice * stock;
            const row = document.createElement('tr');
            row.dataset.identity = identity;
            row.dataset.subtotal = subtotal;
            row.dataset.stock = stock;
            row.innerHTML = `
                <td class="px-3 py-3 text-sm"><p class="font-bold text-gray-800">${escapeHtml(item.code)} — ${escapeHtml(item.name)}</p><p class="text-xs text-gray-500">${escapeHtml(item.lot_number || 'Lote automático')}</p>
                    ${hidden(`products[${index}][product_id]`, item.product_id || '')}${hidden(`products[${index}][code]`, item.code)}${hidden(`products[${index}][name]`, item.name)}${hidden(`products[${index}][category]`, item.category)}${hidden(`products[${index}][description]`, item.description || '')}${hidden(`products[${index}][lot_number]`, item.lot_number || '')}
                </td>
                <td class="px-3 py-3 text-sm">S/. ${purchasePrice.toFixed(2)}${hidden(`products[${index}][purchase_price]`, purchasePrice)}</td>
                <td class="px-3 py-3 text-sm font-semibold text-emerald-700">S/. ${salePrice.toFixed(2)}${hidden(`products[${index}][sale_price]`, salePrice)}</td>
                <td class="px-3 py-3 text-center text-sm font-bold">${stock}${hidden(`products[${index}][stock]`, stock)}</td>
                <td class="px-3 py-3 text-right text-sm font-black">S/. ${subtotal.toFixed(2)}</td>
                <td class="px-3 py-3 text-right"><button type="button" class="rounded px-2 py-1 font-bold text-red-500 hover:bg-red-50" aria-label="Quitar producto">×</button></td>`;
            row.querySelector('button').addEventListener('click', () => { row.remove(); updateTotals(); });
            tableBody.appendChild(row);
            clearProductFields();
            updateTotals();
        }

        function hidden(name, value) {
            return `<input type="hidden" name="${escapeHtml(name)}" value="${escapeHtml(String(value))}">`;
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>'"]/g, character => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[character]));
        }

        function clearProductFields() {
            Object.values(fields).forEach(field => field.value = '');
            fields.existing.focus();
        }

        function updateTotals() {
            const rows = [...tableBody.querySelectorAll('tr[data-identity]')];
            const total = rows.reduce((sum, row) => sum + Number(row.dataset.subtotal), 0);
            const units = rows.reduce((sum, row) => sum + Number(row.dataset.stock), 0);
            document.getElementById('totalInvestmentLabel').textContent = `S/. ${total.toFixed(2)}`;
            document.getElementById('totalUnitsLabel').textContent = `${units} unidades`;
            document.getElementById('itemsBadge').textContent = `${rows.length} ${rows.length === 1 ? 'producto' : 'productos'}`;
            document.getElementById('fundingSection').classList.toggle('hidden', rows.length === 0);
            if (!rows.length && !document.getElementById('emptyRow')) tableBody.appendChild(emptyRow);

            const fromSales = document.querySelector('input[name="funding_source"]:checked')?.value === 'sales_revenue';
            const insufficient = fromSales && total > availableSalesBalance;
            document.getElementById('insufficientBalanceAlert').classList.toggle('hidden', !insufficient);
            document.getElementById('btnSaveAll').disabled = insufficient;
            document.getElementById('btnSaveAll').classList.toggle('opacity-50', insufficient);
        }

        document.querySelectorAll('input[name="funding_source"]').forEach(input => input.addEventListener('change', updateTotals));
        Object.values(oldItems).forEach(item => addItem(item));
    </script>
</x-app-layout>

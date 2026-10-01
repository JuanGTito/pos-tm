<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ingreso y Compra de Mercadería') }}
            </h2>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg">
                    Disponible de Ventas: <strong>S/. {{ number_format($availableSalesBalance, 2) }}</strong>
                </span>
                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg">
                    Inversión Externa Aportada: <strong>S/. {{ number_format($totalNewInvestment, 2) }}</strong>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Columna Izquierda: Formulario para añadir a la lista -->
                <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200 h-fit">
                    <h3 class="text-lg font-bold mb-4 text-gray-700 border-b pb-2 flex items-center gap-2">
                        <span>📦</span> Datos del Producto
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select id="temp_category" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                                <option value="">-- Seleccione --</option>
                                @foreach($categories as $prefix => $name)
                                    <option value="{{ $prefix }}">{{ $prefix }} - {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código</label>
                            <input type="text" id="temp_code" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: 10A001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Producto</label>
                            <input type="text" id="temp_name" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: Arroz Costeño 1kg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                            <textarea id="temp_description" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" placeholder="Detalles o especificaciones..."></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">P. Compra (S/.)</label>
                                <input type="number" id="temp_price" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">P. Venta Final (S/.)</label>
                                <input type="number" id="temp_sale_price" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: +20%">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cantidad / Unidades a Ingresar</label>
                            <input type="number" id="temp_stock" min="1" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="10">
                        </div>
                        <button type="button" id="btnAddToList" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg hover:bg-indigo-700 font-bold transition shadow-sm">
                            + Agregar a la Lista de Compra
                        </button>
                    </div>
                </div>

                <!-- Columna Derecha: Lista y Procesamiento de la Inversión -->
                <div class="lg:col-span-2 bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200">
                    <form action="{{ route('products.store') }}" method="POST" id="purchaseForm">
                        @csrf
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-lg font-bold text-gray-700">Mercadería a Procesar</h3>
                            <span id="itemsBadge" class="text-xs px-2.5 py-1 bg-gray-100 font-semibold rounded-full text-gray-600">0 productos</span>
                        </div>

                        <div class="overflow-x-auto min-h-[220px]">
                            <table class="min-w-full divide-y divide-gray-200" id="productsTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Código</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Producto</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">P. Compra</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">P. Venta</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Cantidad</th>
                                        <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Inversión</th>
                                        <th class="px-3 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                                    <tr id="emptyRow">
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm italic">
                                            Añade productos desde el formulario izquierdo para procesar el ingreso.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Sección de Financiamiento y Confirmación (Visible cuando hay ítems) -->
                        <div id="fundingSection" class="mt-8 border-t pt-6 hidden">
                            <!-- Resumen Total -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Costo Total de Inversión</p>
                                    <h4 class="text-2xl font-black text-indigo-700" id="totalInvestmentLabel">S/. 0.00</h4>
                                </div>
                                <div class="mt-2 sm:mt-0 text-sm text-gray-600">
                                    <span id="totalUnitsLabel">0 unidades</span> en total
                                </div>
                            </div>

                            <!-- Selector de Origen de Fondos -->
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">
                                ¿Cómo se financia este ingreso de mercadería?
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <label class="relative flex flex-col p-4 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:border-emerald-500 transition-all has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/20 has-[:checked]:ring-2 has-[:checked]:ring-emerald-600/20">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-gray-900 flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                            Nueva Inversión
                                        </span>
                                        <input type="radio" name="funding_source" value="new_investment" checked class="text-emerald-600 focus:ring-emerald-500">
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Capital externo inyectado al negocio. El dinero recaudado por ventas queda intacto.
                                    </p>
                                </label>

                                <label class="relative flex flex-col p-4 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/20 has-[:checked]:ring-2 has-[:checked]:ring-blue-600/20">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-gray-900 flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                            Alzar de Ventas Acumuladas
                                        </span>
                                        <input type="radio" name="funding_source" value="sales_revenue" class="text-blue-600 focus:ring-blue-500">
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Se financia con las ventas acumuladas.
                                        <span class="block mt-1 font-semibold text-blue-700">Disponible: S/. {{ number_format($availableSalesBalance, 2) }}</span>
                                    </p>
                                </label>
                            </div>

                            <!-- Alerta si excede saldo disponible -->
                            <div id="insufficientBalanceAlert" class="p-3 mb-4 bg-amber-50 border border-amber-300 rounded-lg text-amber-800 text-xs font-semibold hidden">
                                ⚠️ Atención: El costo total (S/. <span id="alertTotal"></span>) supera el saldo disponible de ventas (S/. {{ number_format($availableSalesBalance, 2) }}). Debes elegir "Nueva Inversión" o reducir unidades.
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Notas u Observaciones (Opcional)</label>
                                <input type="text" name="notes" placeholder="Ej: Proveedor Distribuidora Central, Factura 001-450" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" id="btnSaveAll" class="bg-indigo-600 text-white px-8 py-3 rounded-xl hover:bg-indigo-700 font-bold shadow-lg transition">
                                    Registrar Ingreso de Mercadería
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
    const availableSalesBalance = {{ (float) $availableSalesBalance }};
    const btnAdd = document.getElementById('btnAddToList');
    const tableBody = document.getElementById('tableBody');
    const emptyRow = document.getElementById('emptyRow');
    const fundingSection = document.getElementById('fundingSection');
    const totalInvestmentLabel = document.getElementById('totalInvestmentLabel');
    const totalUnitsLabel = document.getElementById('totalUnitsLabel');
    const itemsBadge = document.getElementById('itemsBadge');
    const inputCode = document.getElementById('temp_code');
    const selectCategory = document.getElementById('temp_category');
    const insufficientBalanceAlert = document.getElementById('insufficientBalanceAlert');
    const btnSaveAll = document.getElementById('btnSaveAll');

    let totalInvestment = 0;
    let totalUnits = 0;
    let addedCount = 0;

    selectCategory.addEventListener('change', function() {
        if(this.value) {
            inputCode.value = this.value; 
            inputCode.focus();
        }
    });

    inputCode.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('temp_name').focus();
        }
    });

    btnAdd.addEventListener('click', function() {
        const code = inputCode.value.trim();
        const name = document.getElementById('temp_name').value.trim();
        const description = document.getElementById('temp_description').value.trim();
        const purchasePrice = parseFloat(document.getElementById('temp_price').value);
        let salePrice = parseFloat(document.getElementById('temp_sale_price').value);
        const stock = parseInt(document.getElementById('temp_stock').value);

        if(!code || !name) {
            alert('El código y el nombre del producto son obligatorios.');
            return;
        }

        if(isNaN(purchasePrice) || purchasePrice < 0) {
            alert('Ingresa un precio de compra válido.');
            return;
        }

        if(isNaN(stock) || stock < 1) {
            alert('Ingresa una cantidad válida de stock (mínimo 1).');
            return;
        }

        if(isNaN(salePrice) || salePrice <= 0) {
            salePrice = parseFloat((purchasePrice * 1.20).toFixed(2));
        }

        const subtotal = purchasePrice * stock;

        // Quitar fila vacía si existe
        if (emptyRow && emptyRow.parentNode) {
            emptyRow.remove();
        }

        const row = document.createElement('tr');
        row.className = "hover:bg-gray-50 transition-colors";
        row.dataset.subtotal = subtotal;
        row.dataset.stock = stock;

        row.innerHTML = `
            <td class="px-3 py-3 text-sm font-bold text-indigo-700">${code}
                <input type="hidden" name="products[${code}][code]" value="${code}">
            </td>
            <td class="px-3 py-3 text-sm font-medium text-gray-800">${name}
                <input type="hidden" name="products[${code}][name]" value="${name}">
                <input type="hidden" name="products[${code}][description]" value="${description}">
            </td>
            <td class="px-3 py-3 text-sm text-gray-700">S/. ${purchasePrice.toFixed(2)}
                <input type="hidden" name="products[${code}][purchase_price]" value="${purchasePrice}">
            </td>
            <td class="px-3 py-3 text-sm text-emerald-700 font-semibold">S/. ${salePrice.toFixed(2)}
                <input type="hidden" name="products[${code}][sale_price]" value="${salePrice}">
            </td>
            <td class="px-3 py-3 text-sm font-bold text-center">${stock}
                <input type="hidden" name="products[${code}][stock]" value="${stock}">
            </td>
            <td class="px-3 py-3 text-sm font-black text-right text-gray-900">S/. ${subtotal.toFixed(2)}</td>
            <td class="px-3 py-3 text-right">
                <button type="button" class="text-red-500 hover:text-red-700 font-bold px-2 py-1 rounded" onclick="removeRow(this)">×</button>
            </td>
        `;

        tableBody.appendChild(row);
        addedCount++;
        clearInputs();
        updateTotals();
    });

    window.removeRow = function(btn) {
        btn.closest('tr').remove();
        addedCount--;
        if (tableBody.children.length === 0) {
            tableBody.appendChild(emptyRow);
        }
        updateTotals();
    };

    function updateTotals() {
        totalInvestment = 0;
        totalUnits = 0;

        const rows = tableBody.querySelectorAll('tr[data-subtotal]');
        rows.forEach(r => {
            totalInvestment += parseFloat(r.dataset.subtotal || 0);
            totalUnits += parseInt(r.dataset.stock || 0);
        });

        totalInvestmentLabel.textContent = `S/. ${totalInvestment.toFixed(2)}`;
        totalUnitsLabel.textContent = `${totalUnits} unidades`;
        itemsBadge.textContent = `${rows.length} ${rows.length === 1 ? 'producto' : 'productos'}`;

        fundingSection.classList.toggle('hidden', rows.length === 0);

        validateBalance();
    }

    function validateBalance() {
        const selectedSource = document.querySelector('input[name="funding_source"]:checked')?.value;
        if (selectedSource === 'sales_revenue' && totalInvestment > availableSalesBalance) {
            document.getElementById('alertTotal').textContent = totalInvestment.toFixed(2);
            insufficientBalanceAlert.classList.remove('hidden');
            btnSaveAll.disabled = true;
            btnSaveAll.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            insufficientBalanceAlert.classList.add('hidden');
            btnSaveAll.disabled = false;
            btnSaveAll.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    document.querySelectorAll('input[name="funding_source"]').forEach(radio => {
        radio.addEventListener('change', validateBalance);
    });

    function clearInputs() {
        inputCode.value = '';
        document.getElementById('temp_name').value = '';
        document.getElementById('temp_description').value = '';
        document.getElementById('temp_price').value = '';
        document.getElementById('temp_sale_price').value = '';
        document.getElementById('temp_stock').value = '';
        selectCategory.value = '';
        inputCode.focus();
    }
    </script>
</x-app-layout>
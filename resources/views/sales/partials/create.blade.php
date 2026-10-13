<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Venta directa') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('sales.store') }}" method="POST" id="formSale">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-200">
                            <h3 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-widest">Información del Cliente</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" id="customerDNI" name="customer_dni" placeholder="DNI / Documento" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500" required>
                                <input type="text" id="customerName" name="customer_name" placeholder="Nombre Completo" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-200">
                            <h3 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-widest">Buscador de Productos</h3>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="searchProducto" placeholder="Escanea código de barras o escribe nombre..." 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 bg-gray-50 text-lg" autocomplete="off" autofocus>
                                
                                <div id="productResults" class="absolute left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-2xl mt-1 hidden z-50 max-h-96 overflow-y-auto">
                                </div>
                            </div>
                            
                            <p class="mt-4 text-xs text-gray-400 italic text-center">Usa el lector de barras en cualquier momento para agregar directo.</p>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="bg-white shadow-xl rounded-lg border border-gray-200 flex flex-col sticky top-6" style="height: calc(100vh - 150px);">
                            <div class="p-4 border-b bg-gray-50 rounded-t-lg">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Resumen de Venta
                                </h3>
                            </div>

                            <div id="productosContainer" class="flex-grow overflow-y-auto p-4 space-y-3 bg-gray-50/50">
                                <div id="emptyProductsMessage" class="text-center py-20 text-gray-400">
                                    <p class="text-5xl mb-4">🛒</p>
                                    <p>Tu carrito está vacío</p>
                                </div>
                            </div>

                            <div class="p-6 border-t bg-white rounded-b-lg space-y-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Subtotal</span>
                                        <span class="font-semibold">S/. <span id="subtotalTotal">0.00</span></span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>IGV (19%)</span>
                                        <span class="font-semibold">S/. <span id="impuestoTotal">0.00</span></span>
                                    </div>
                                    <div class="flex justify-between text-2xl font-black text-indigo-700 border-t pt-2">
                                        <span>TOTAL</span>
                                        <span>S/. <span id="totalSale">0.00</span></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <a href="{{ route('sales.index') }}" class="px-4 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-center hover:bg-gray-200 transition">
                                        Cancelar
                                    </a>
                                    <button type="submit" id="btnSubmit" disabled 
                                        class="px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed shadow-lg shadow-indigo-200 transition-all w-full uppercase tracking-wider">
                                        PAGAR (F10)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
    let itemCount = 0;
    let barcodeBuffer = "";
    let lastKeyTime = Date.now();
    let typingTimer;

    const searchProducto = document.getElementById('searchProducto');
    const productResults = document.getElementById('productResults');
    const container = document.getElementById('productosContainer');

    // ==================== 1. ESCUCHA GLOBAL E HÍBRIDA ====================
    window.addEventListener('keydown', async function(e) {
        const currentTime = Date.now();
        const diff = currentTime - lastKeyTime;
        lastKeyTime = currentTime;

        // DETECCIÓN DE LECTOR (Escritura veloz)
        if (diff < 50) { 
            if (e.key === 'Enter') {
                e.preventDefault();
                if (barcodeBuffer.length > 0) {
                    await buscarYAgregarDirecto(barcodeBuffer);
                    barcodeBuffer = "";
                }
            } else if (e.key.length === 1) {
                barcodeBuffer += e.key;
            }
        } else {
            // DETECCIÓN DE HUMANO (Escritura lenta)
            if (e.key === 'Enter' && document.activeElement === searchProducto) {
                e.preventDefault();
                const query = searchProducto.value.trim();
                if (query.length > 0) await buscarYAgregarDirecto(query);
            }
            // Reset de buffer si el ritmo es humano
            if (e.key.length === 1) barcodeBuffer = e.key;
        }
    });

    // ==================== 2. BUSCADOR VISUAL (SUGERENCIAS) ====================
    searchProducto.addEventListener('input', function() {
        clearTimeout(typingTimer);
        const query = this.value.trim();

        if (query.length >= 2) {
            typingTimer = setTimeout(async () => {
                const response = await fetch(`{{ route('api.search-products') }}?q=${encodeURIComponent(query)}`);
                const productos = await response.json();

                if (productos.length > 0) {
                    productResults.innerHTML = productos.map(p => `
                        <div class="p-4 border-b border-gray-100 cursor-pointer hover:bg-indigo-600 hover:text-white transition-all group" 
                             onclick="gestionarAgregado(${JSON.stringify(p).replace(/"/g, '&quot;')})">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="block font-bold text-sm group-hover:text-white text-gray-800">${p.code}</span>
                                    <span class="block text-xs group-hover:text-white text-gray-600">${p.name}</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-indigo-600 group-hover:text-white">S/. ${p.sale_price}</span>
                                    <p class="text-[10px] text-gray-400 group-hover:text-indigo-200">Stock: ${p.stock}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    productResults.classList.remove('hidden');
                } else {
                    productResults.classList.add('hidden');
                }
            }, 200); // Pequeña espera para no saturar el servidor
        } else {
            productResults.classList.add('hidden');
        }
    });

    // ==================== 3. LÓGICA DE AGREGADO DIRECTO ====================
    async function buscarYAgregarDirecto(query) {
        try {
            const response = await fetch(`{{ route('api.search-products') }}?q=${encodeURIComponent(query)}`);
            const productos = await response.json();

            if (productos.length > 0) {
                // Si escaneamos, buscamos el que coincida exacto o el primero
                const producto = productos.find(p => p.code === query) || productos[0];
                gestionarAgregado(producto);
                
                searchProducto.value = '';
                productResults.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function gestionarAgregado(producto) {
        const filaExistente = document.querySelector(`.producto-item[data-product-id="${producto.id}"]`);
        
        if (filaExistente) {
            const inputCant = filaExistente.querySelector('.cantidad-input');
            const nuevaCant = parseInt(inputCant.value) + 1;
            if (nuevaCant <= producto.stock) {
                inputCant.value = nuevaCant;
                actualizarSubtotal(inputCant);
                filaExistente.classList.add('bg-green-100');
                setTimeout(() => filaExistente.classList.remove('bg-green-100'), 300);
            }
        } else {
            agregarProductoAlCarrito(producto.id, producto.code, producto.name, producto.sale_price, producto.stock);
        }
        searchProducto.value = '';
        productResults.classList.add('hidden');
        searchProducto.focus();
    }

    // ==================== 4. RENDERIZADO Y TOTALES ====================
    function agregarProductoAlCarrito(id, code, name, price, stock) {
        const emptyMessage = document.getElementById('emptyProductsMessage');
        if (emptyMessage) emptyMessage.remove();

        const index = itemCount++;
        const html = `
            <div class="producto-item bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-3 transition-all hover:shadow-md" 
                 data-item="${index}" data-product-id="${id}">

                <div class="flex justify-between items-start mb-2">
                    <div class="flex-grow">
                        <h4 class="text-sm font-bold text-gray-800 uppercase leading-tight">${name}</h4>
                        <span class="text-[10px] font-mono text-gray-400 bg-gray-100 px-1 rounded">${code}</span>
                    </div>
                    <button type="button" class="text-red-400 hover:text-red-600 transition ml-2" 
                            onclick="this.closest('.producto-item').remove(); actualizarTotales();">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase">Cantidad</span>
                            <input type="number" name="items[${index}][quantity]" value="1" min="1" max="${stock}" 
                                class="w-16 h-8 text-sm border-gray-300 rounded-md cantidad-input focus:ring-indigo-500" 
                                onchange="actualizarSubtotal(this)">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase">Precio Unit.</span>
                            <span class="text-xs font-semibold text-gray-600">S/. ${parseFloat(price).toFixed(2)}</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-[9px] font-bold text-gray-400 uppercase block">Subtotal</span>
                        <span class="text-base font-black text-indigo-600 subtotal-display">S/. ${parseFloat(price).toFixed(2)}</span>

                        <input type="hidden" name="items[${index}][product_id]" value="${id}">
                        <input type="hidden" name="items[${index}][price]" value="${price}">
                        <input type="hidden" name="items[${index}][subtotal]" class="subtotal-value" value="${price}">
                    </div>
                </div>
            </div>`;
        
        container.insertAdjacentHTML('beforeend', html);
        actualizarTotales();
        validarFormulario();
    }

    function actualizarSubtotal(input) {
        const item = input.closest('.producto-item');
        const cant = parseFloat(item.querySelector('.cantidad-input').value) || 0;
        const prec = parseFloat(item.querySelector('input[name*="[price]"]').value) || 0;
        const sub = cant * prec;
        item.querySelector('.subtotal-display').textContent = 'S/. ' + sub.toFixed(2);
        item.querySelector('.subtotal-value').value = sub.toFixed(2);
        actualizarTotales();
    }

    function actualizarTotales() {
        let total = 0;
        document.querySelectorAll('.subtotal-value').forEach(el => total += parseFloat(el.value) || 0);
        const subtotal = total / 1.19;
        const impuesto = total - subtotal;
        document.getElementById('subtotalTotal').textContent = subtotal.toFixed(2);
        document.getElementById('impuestoTotal').textContent = impuesto.toFixed(2);
        document.getElementById('totalSale').textContent = total.toFixed(2);
    }

    function validarFormulario() {
        const btn = document.getElementById('btnSubmit');
        const dni = document.getElementById('customerDNI').value.trim();
        const count = document.querySelectorAll('.producto-item').length;
        btn.disabled = !(dni && count > 0);
    }

    document.getElementById('customerDNI').addEventListener('input', validarFormulario);
</script>

    </x-app-layout>

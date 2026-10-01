<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensaje de éxito -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Card Principal -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Cabecera con título y botón -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Productos Disponibles</h3>
                        <a href="{{ route('products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            + Nuevo Producto
                        </a>
                    </div>

                    <!-- Buscador dinámico -->
                    <div class="mb-6">
                        <div class="flex gap-3">
                            <input 
                                type="text" 
                                id="searchInput" 
                                placeholder="Buscar por código, nombre o descripción..." 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value="{{ $search }}"
                            >
                            <button id="searchBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Buscar
                            </button>
                            @if($search)
                                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition-colors">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Tabla de productos (contenedor que se actualiza con AJAX) -->
                    <div id="productsTable">
                        @include('products.partials.table', compact('products', 'search'))
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para búsqueda dinámica -->
    <script>
        document.getElementById('searchBtn').addEventListener('click', function() {
            performSearch();
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });

        function performSearch() {
            const search = document.getElementById('searchInput').value;
            
            // Realiza petición AJAX al servidor
            fetch(`{{ route('products.index') }}?search=${encodeURIComponent(search)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            })
            .then(response => response.text())
            .then(html => {
                // Reemplaza solo la tabla
                document.getElementById('productsTable').innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
        }

        function editProduct(id) {
            window.location.href = `/products/${id}/edit`;
        }

        function deleteProduct(id) {
            if (confirm('¿Está seguro de que desea eliminar este producto?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/products/${id}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
{{-- 
    Tabla atómica simplificada - Optimizada para pantalla completa
    @param array $headers - Encabezados de la tabla
    @param array $rows - Filas de datos
--}}
<div class="overflow-x-auto">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b border-gray-300">
                @foreach($headers as $header)
                    <th class="px-md py-md text-left text-sm font-semibold text-gray-700">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>

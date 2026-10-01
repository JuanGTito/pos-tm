<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Componentes del Sistema</h1>
            <span class="text-sm text-gray-500">Frontend Atomizado</span>
        </div>
    </x-slot>

    <div class="grid grid-cols-2 gap-md">
        <!-- Sección: Botones -->
        <x-molecules.card title="Botones">
            <div class="flex flex-wrap gap-sm">
                <x-atoms.button variant="primary">Guardar</x-atoms.button>
                <x-atoms.button variant="secondary">Cancelar</x-atoms.button>
                <x-atoms.button variant="danger">Eliminar</x-atoms.button>
                <x-atoms.button size="sm">Pequeño</x-atoms.button>
            </div>
        </x-molecules.card>

        <!-- Sección: Inputs y Labels -->
        <x-molecules.card title="Formulario">
            <div class="space-y-sm">
                <x-molecules.form-group 
                    label="Nombre" 
                    placeholder="Ej: Producto"
                />
                <x-molecules.form-group 
                    label="Precio" 
                    inputType="number"
                    placeholder="0.00"
                />
            </div>
        </x-molecules.card>

        <!-- Sección: Badges -->
        <x-molecules.card title="Badges">
            <div class="flex gap-sm flex-wrap">
                <x-atoms.badge>Normal</x-atoms.badge>
                <x-atoms.badge variant="primary">Activo</x-atoms.badge>
                <x-atoms.badge variant="danger">Peligro</x-atoms.badge>
            </div>
        </x-molecules.card>

        <!-- Sección: Alertas -->
        <x-molecules.card title="Alertas">
            <div class="space-y-sm">
                <x-molecules.alert type="info" title="Info" closeable="false">
                    Mensaje informativo
                </x-molecules.alert>
                <x-molecules.alert type="success" title="Éxito" closeable="false">
                    Operación completada
                </x-molecules.alert>
            </div>
        </x-molecules.card>

        <!-- Sección: Tabla Pequeña -->
        <x-molecules.card title="Tabla de Productos">
            <x-molecules.table :headers="['Producto', 'Precio', 'Stock']">
                <x-molecules.table-row>
                    <x-molecules.table-cell>Laptop</x-molecules.table-cell>
                    <x-molecules.table-cell>$899.99</x-molecules.table-cell>
                    <x-molecules.table-cell><x-atoms.badge variant="primary">5</x-atoms.badge></x-molecules.table-cell>
                </x-molecules.table-row>
                <x-molecules.table-row>
                    <x-molecules.table-cell>Mouse</x-molecules.table-cell>
                    <x-molecules.table-cell>$29.99</x-molecules.table-cell>
                    <x-molecules.table-cell><x-atoms.badge variant="primary">15</x-atoms.badge></x-molecules.table-cell>
                </x-molecules.table-row>
            </x-molecules.table>
        </x-molecules.card>

        <!-- Sección: Textos -->
        <x-molecules.card title="Estilos de Texto">
            <div class="space-y-sm">
                <x-atoms.text size="sm" color="muted">Texto pequeño secundario</x-atoms.text>
                <x-atoms.text weight="semibold">Texto con peso semibold</x-atoms.text>
                <x-atoms.text size="lg" weight="bold">Texto grande en negrita</x-atoms.text>
            </div>
        </x-molecules.card>
    </div>
</x-app-layout>

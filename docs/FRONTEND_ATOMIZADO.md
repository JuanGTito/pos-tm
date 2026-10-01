# Frontend Atomizado - Guía de Uso

## 📋 Sistema Limpio y Escalable

---

## 🔹 Componentes Atómicos

### `x-atoms.box`
Contenedor base con bordes y espaciado.
```blade
<x-atoms.box padding="md" rounded="base">
    Contenido
</x-atoms.box>
```

### `x-atoms.button`
Botón reutilizable en 3 variantes.
```blade
<x-atoms.button variant="primary">Guardar</x-atoms.button>
<x-atoms.button variant="secondary">Cancelar</x-atoms.button>
<x-atoms.button variant="danger" size="sm">Eliminar</x-atoms.button>
```
**Props**: `variant` (primary, secondary, danger), `size` (xs, sm, md, lg), `type` (button, submit, reset)

### `x-atoms.input`
Campo de entrada optimizado.
```blade
<x-atoms.input 
    type="text" 
    placeholder="Escribe aquí"
    name="nombre"
/>
```

### `x-atoms.label`
Etiqueta de formulario.
```blade
<x-atoms.label>Nombre del producto</x-atoms.label>
```

### `x-atoms.badge`
Insignia pequeña para estados.
```blade
<x-atoms.badge variant="primary">Activo</x-atoms.badge>
<x-atoms.badge variant="danger">Peligro</x-atoms.badge>
```

### `x-atoms.text`
Párrafo con estilos predefinidos.
```blade
<x-atoms.text size="lg" weight="bold">Texto grande</x-atoms.text>
<x-atoms.text color="muted" size="sm">Texto secundario</x-atoms.text>
```

### `x-atoms.divider`
Línea divisora.
```blade
<x-atoms.divider spacing="md" />
```

### `x-atoms.spinner`
Indicador de carga.
```blade
<x-atoms.spinner size="md" />
```

---

## 🔗 Componentes Moleculares

### `x-molecules.card`
Tarjeta contenedora con título opcional.
```blade
<x-molecules.card title="Mi Sección">
    Contenido
</x-molecules.card>
```

### `x-molecules.form-group`
Label + Input combinados.
```blade
<x-molecules.form-group 
    label="Correo" 
    inputType="email"
    placeholder="usuario@example.com"
/>
```

2. **x-molecules.form-group** - Grupo de label + input
   ```blade
   <x-molecules.form-group 
       label="Nombre" 
       inputType="text"
       placeholder="Ej: Juan"
       shortcut="F1"
   />
   ```

3. **x-molecules.table** - Tabla con encabezados
   ```blade
   <x-molecules.table :headers="['Nombre', 'Email', 'Acciones']">
       <x-molecules.table-row>
           <x-molecules.table-cell>Juan</x-molecules.table-cell>
           <x-molecules.table-cell>juan@email.com</x-molecules.table-cell>
           <x-molecules.table-cell>...</x-molecules.table-cell>
       </x-molecules.table-row>
   </x-molecules.table>
   ```

4. **x-molecules.alert** - Mensaje de alerta
   ```blade
   <x-molecules.alert type="success" title="Éxito" closeable="true">
       Operación completada
   </x-molecules.alert>
   ```
   - **type**: info, success, warning, danger

---

## 🎨 Paleta de Colores

Todo el sistema usa **escala de grises** para facilitar cambios futuros:

```
primary-50:  #f9fafb  (Muy claro)
primary-100: #f3f4f6  (Claro)
primary-200: #e5e7eb  (Borde claro)
primary-300: #d1d5db  (Borde)
primary-400: #9ca3af  (Texto secundario)
primary-500: #6b7280  (Texto principal)
primary-600: #4b5563  (Texto oscuro)
primary-700: #374151  (Muy oscuro)
primary-800: #1f2937  (Casi negro)
primary-900: #111827  (Negro)
```

### Cambiar Colores:
Modifica `tailwind.config.js` → `theme.extend.colors.primary`

---

## 📏 Escala de Espaciado

```
xs:  4px    | sm:  8px   | md:  16px
lg:  24px   | xl:  32px  | 2xl: 48px
3xl: 64px
```

**Uso en clases**:
```blade
<div class="p-md mb-lg mt-sm">Contenido</div>
```

---

## 🎨 Paleta de Colores

Escala neutra de **9 tonos de gris**:
```
primary-50:  #f9fafb  (Muy claro)
primary-100: #f3f4f6  (Claro)
primary-200: #e5e7eb  (Borde claro)
primary-300: #d1d5db  (Borde)
primary-400: #9ca3af  (Texto secundario)
primary-500: #6b7280  (Texto principal)
primary-600: #4b5563  (Oscuro)
primary-700: #374151  (Muy oscuro)
primary-800: #1f2937  (Casi negro)
primary-900: #111827  (Negro)
```

**Cambiar paleta**: Modifica `tailwind.config.js` → `theme.extend.colors.primary`

---

## 🎯 Ejemplos Prácticos

### Formulario Completo
```blade
<x-molecules.card title="Crear Producto">
    <form method="POST" action="{{ route('products.store') }}" class="space-y-md">
        @csrf
        
        <x-molecules.form-group 
            label="Nombre" 
            name="name"
            placeholder="Nombre del producto"
        />
        
        <x-molecules.form-group 
            label="Precio" 
            inputType="number"
            name="price"
            placeholder="0.00"
        />
        
        <div class="flex gap-md">
            <x-atoms.button variant="primary" type="submit">
                Guardar
            </x-atoms.button>
            <x-atoms.button variant="secondary" type="reset">
                Limpiar
            </x-atoms.button>
        </div>
    </form>
</x-molecules.card>
```

### Tabla con Datos
```blade
<x-molecules.card title="Productos">
    <x-molecules.table :headers="['Nombre', 'Precio', 'Stock', 'Acciones']">
        @foreach($products as $product)
            <x-molecules.table-row>
                <x-molecules.table-cell>{{ $product->name }}</x-molecules.table-cell>
                <x-molecules.table-cell>${{ number_format($product->price, 2) }}</x-molecules.table-cell>
                <x-molecules.table-cell>
                    <x-atoms.badge variant="primary">
                        {{ $product->stock }}
                    </x-atoms.badge>
                </x-molecules.table-cell>
                <x-molecules.table-cell>
                    <div class="flex gap-sm">
                        <x-atoms.button size="sm" variant="secondary">Ver</x-atoms.button>
                        <x-atoms.button size="sm" variant="secondary">Editar</x-atoms.button>
                        <x-atoms.button size="sm" variant="danger">Eliminar</x-atoms.button>
                    </div>
                </x-molecules.table-cell>
            </x-molecules.table-row>
        @endforeach
    </x-molecules.table>
</x-molecules.card>
```

### Alertas
```blade
@if($errors->any())
    <x-molecules.alert type="danger" title="Errores de Validación" closeable="false">
        Revisa los campos marcados
    </x-molecules.alert>
@endif

@if(session('success'))
    <x-molecules.alert type="success" title="Éxito">
        {{ session('success') }}
    </x-molecules.alert>
@endif
```

---

## 🔄 Personalización

### 1. Cambiar Colores
Editar `tailwind.config.js`:
```javascript
colors: {
    primary: {
        50: '#f0f4ff',   // Tu color claro
        900: '#003d99',  // Tu color oscuro
    }
}
```

### 2. Cambiar Espaciado
Editar `tailwind.config.js`:
```javascript
spacing: {
    xs: '6px',  // Aumentar
    md: '20px', // Cambiar
}
```

### 3. Crear Variante de Componente
Crear nuevo archivo en `resources/views/components/atoms/`:
```blade
{{-- Ejemplo: x-atoms.button-large --}}
<button class="px-lg py-md text-lg font-bold rounded-lg">
    {{ $slot }}
</button>
```

---

## 📝 Notas Importantes

✅ **Sin dependencias** - Solo Tailwind CSS y Alpine.js  
✅ **Totalmente atómico** - Reutilizable y combinable  
✅ **Escalable** - Colores desde un punto central  
✅ **Limpio** - Paleta neutra, sin saturación de colores  
✅ **Responsivo** - Funciona en todos los tamaños  
✅ **Optimizado** - Tablas amplias, cards compactos  
✅ **Demo incluida** - Ver en `/components-demo`  

⚠️ **Cambios recientes**:
- ❌ Removido: Sistema de atajos de teclado
- ✅ Mejorado: Proporciones de tablas (más altura)
- ✅ Mejorado: Cards compactos (menos padding)
- ✅ Mejorado: Layout sin necesidad de scroll

---

## 🚀 Próximos Pasos

1. Integrar componentes en vistas de módulos (Productos, Ventas, Caja)
2. Crear variantes específicas si es necesario
3. Implementar tema oscuro opcional
4. Añadir animaciones suaves si es requerido

---

**Versión Final: Enero 2026**  
**Estado**: Producción

---

## 🚀 Próximos Pasos

1. Integrar este sistema en todas las vistas existentes
2. Crear variantes de componentes específicas si es necesario
3. Añadir más acciones personalizadas en el sistema de atajos
4. Implementar temas oscuro/claro cuando sea requerido

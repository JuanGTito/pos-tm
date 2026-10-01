# Sistema de Ventas e Inventario - Documentación

## Arquitectura General

El sistema está dividido en dos módulos principales interconectados:

### 1. Módulo de Productos e Inversiones (Inventario)
- **Ubicación:** `/products`
- **Función:** Gestión del catálogo, reposición/inversión y stock
- **Modelos:** `Product`, `ProductPurchase`
- **Características:**
  - Catálogo de productos y categorías
  - Búsqueda dinámica en tiempo real
  - Control de stock mínimo y alertas
  - Registro de compras e ingreso de mercadería
  - Clasificación de fuente de financiamiento: **Nueva Inversión** vs **Alzar del Ingreso Acumulado**
  - Cálculo de inversión (\(precio\_compra \times cantidad\)) y precio final de venta

### 2. Módulo de Ventas (Core)
- **Ubicación:** `/sales`
- **Función:** Registrar y gestionar transacciones de venta directa
- **Modelos:** `Sale`, `SaleDetail`, `Customer`
- **Características:**
  - Crear nuevas ventas directamente en cualquier momento
  - Seleccionar o registrar clientes al instante
  - Búsqueda en vivo de productos y cantidades
  - Cálculo automático de subtotales e impuestos
  - Decremento automático de stock

## Flujo de Venta Completo

```
DASHBOARD (Opción directa de nueva venta)
        ↓
    CREAR VENTA (/sales/create)
        ↓
    Selecciona / Registra Cliente
        ↓
    Agrega Productos
        ├── Reduce stock de PRODUCTS
        └── Calcula total e impuestos
        ↓
    GUARDAR VENTA
        ├── Registra en tabla SALES
        ├── Registra detalles en SALE_DETAILS
        └── Decrementa stock en PRODUCTS
        ↓
    VER DETALLE / COMPROBANTE
```

## Tablas y Relaciones

### Tabla: products
```sql
- id (PK)
- category_id (FK)
- code (único)
- name
- description
- purchase_price
- sale_price
- stock (DECRECE con ventas)
- min_stock
```

### Tabla: sales
```sql
- id (PK)
- user_id (FK) - Quién registró la venta
- customer_id (FK) - Cliente
- sale_date
- total (suma de sale_details)
- tax (19% de total)
- status ('completada')
```

### Tabla: sale_details
```sql
- id (PK)
- sale_id (FK)
- product_id (FK)
- quantity
- price (precio al momento de venta)
- subtotal (qty × price)
```

## Interacciones Clave

### 1. Al Crear Venta
```php
// En SalesController@store
- Valida cliente y productos
- Para cada producto:
  * Verifica stock disponible
  * Crea SaleDetails
  * Decrementa stock: Product::decrement('stock', qty)
- Crea registro en Sales
- Calcula desglose de impuesto
```

### 2. Al Editar Venta
```php
// Restaura stock antiguo (rollback)
- Incrementa stock de items eliminados
- Crea nuevos SaleDetails
- Decrementa stock nuevamente
- Recalcula totales
```

### 3. Al Eliminar Venta
```php
// Restore completo
- Incrementa stock de todos los productos
- Elimina detalles
- Elimina venta
```
- Elimina venta
```

### 4. Transacciones Atómicas
- Usa `DB::beginTransaction()` y `DB::commit()`
- Si hay error, `DB::rollBack()` revierte todos los cambios
- Garantiza integridad de datos

## Dashboard

El dashboard muestra:
- **Total de Productos** (contar)
- **Stock Total** (suma)
- **Stock Bajo** (count donde stock <= min_stock)
- **Botón Nueva Venta** (acceso rápido)
- **Link al Historial de Ventas**

## Flujo Diario Recomendado

```
INICIO DE DÍA / OPERACIÓN
└── Comprobar Stock y alertas de reposición

DURANTE EL DÍA
├── Registrar Ventas directas (desde Dashboard o /sales/create)
│   ├── Seleccionar o registrar cliente
│   ├── Agregar productos y cantidades
│   └── Stock se actualiza en tiempo real
├── Consultar inventario y catálogo de productos
└── Editar o anular ventas si es necesario

FIN DEL DÍA
├── Revisar Historial de Ventas
└── Exportar / Auditar operaciones del día
```

## Validaciones Implementadas

✅ **Stock:** No permite vender más que stock disponible
✅ **Cliente:** Requerido para toda venta
✅ **Productos:** Mínimo uno por venta
✅ **Precios:** Solo números positivos
✅ **Cantidades:** Solo números positivos enteros
✅ **Usuario:** Solo ve sus propias ventas (o todas si es Administrador)

## Cálculos Automáticos

- **Subtotal:** cantidad × precio unitario
- **Total:** suma de subtotales
- **Impuesto:** desglose proporcional sobre el total
- **Stock:** stock_anterior - cantidad_vendida

## Casos de Error Manejados

❌ Stock insuficiente → Rollback + Mensaje de error
❌ Cliente no existe → Validación rechaza
❌ Venta sin productos → Validación rechaza
❌ Acceso no autorizado → Policy bloquea
❌ Datos inválidos → Validación rechaza

## Datos de Prueba

Al correr seeders:
- Roles y permisos (`admin`, `user`)
- Usuarios de prueba: `admin@test.com` y `user@test.com`
- Clientes de ejemplo
- Catálogo de productos con stock variado y precios de compra/venta


## Próximas Mejoras Posibles

- [ ] Reportes por período
- [ ] Exportar a PDF/Excel
- [ ] Devoluciones de venta
- [ ] Descuentos y promociones
- [ ] Búsqueda avanzada de ventas
- [ ] Estadísticas de productos más vendidos
- [ ] Sistema de comisiones
- [ ] Multi-sucursal

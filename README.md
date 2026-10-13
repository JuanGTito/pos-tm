# Control de ventas e inventario

Aplicación Laravel 12 para una tienda de electrónicos y productos no perecederos. Está pensada para hosting compartido con PHP y MySQL, incluido Hostinger.

## Funciones principales

- Venta directa sin apertura ni cierre de caja.
- Descuento automático de stock al vender y restauración al anular una venta.
- Ingreso masivo de mercadería nueva o reposición de productos existentes.
- Historial de ingresos con proveedor, referencia, costo, precio de venta y lote.
- Actualización del costo y precio actual al reponer un producto.
- Separación entre inversión externa y reinversión del fondo acumulado de ventas.
- Gastos operativos por categoría, origen del dinero, notas y foto del comprobante.
- Tablero con ventas, fondo disponible, egresos, reinversión, inversión externa y valor del inventario.
- Alertas de stock mínimo y categorías orientadas a electrónicos.
- Interfaz clara y adaptable con navegación lateral, iconografía uniforme y tipografía legible.
- Configuración de "Mi empresa" para logo, icono del sistema, nombre comercial, RUC, serie de boleta, giro, contacto y ubicación.

No se maneja fecha de vencimiento. El lote se conserva como dato trazable de cada ingreso.

## Desarrollo local

Requisitos: PHP 8.2 o superior, Composer, Node.js y las extensiones PDO/SQLite o PDO/MySQL.

```bash
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Para SQLite, crea `database/database.sqlite` y configura `DB_CONNECTION=sqlite`. Luego:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Pruebas:

```bash
php artisan test
```

## Despliegue en Hostinger

1. Selecciona PHP 8.2 o superior y crea una base de datos MySQL desde hPanel.
2. Sube el proyecto y configura el document root del dominio a la carpeta `public` del proyecto. No expongas `.env`, `app`, `config`, `database` ni `storage` dentro del directorio público.
3. Copia `.env.example` como `.env` y completa `APP_URL`, `APP_KEY`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`. Mantén `APP_ENV=production` y `APP_DEBUG=false`.
4. Compila los recursos antes de subirlos (`npm ci && npm run build`) o ejecútalo por SSH si el plan lo permite. La carpeta `public/build` debe quedar desplegada.
5. Por SSH, desde la raíz del proyecto, ejecuta:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

6. Da permiso de escritura al usuario de PHP sobre `storage` y `bootstrap/cache`.

Las fotos se guardan en `storage/app/public`. Si el plan no permite enlaces simbólicos, crea manualmente en `public/storage` un enlace hacia esa carpeta desde el administrador de archivos o SSH.

No ejecutes los seeders de usuarios de demostración en producción. Crea el administrador con una contraseña propia y segura.

## Flujo financiero implementado

El fondo disponible se calcula como:

```text
ventas completadas - gastos pagados con ventas
```

Cuando una reposición se paga con el fondo de ventas, el sistema crea un gasto enlazado a ese ingreso. Por eso se descuenta una sola vez. Una compra pagada con inversión externa aumenta el inventario, pero no reduce el fondo acumulado de ventas.

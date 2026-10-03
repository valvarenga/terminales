# Terminales Nicaragua

Aplicación Laravel para consultar terminales, horarios y rutas entre municipios de Nicaragua. El área administrativa gestiona catálogos, recorridos, tarifas, sugerencias, usuarios e historial.

## Desarrollo

- PHP 8.2 o superior y las dependencias de `composer.lock`.
- Copiar `.env.example` a `.env` al preparar un entorno nuevo y configurar la base de datos.
- Instalar dependencias con `composer install`, generar la clave con `php artisan key:generate` y aplicar migraciones con `php artisan migrate`.
- Los archivos CSS y JavaScript se sirven directamente desde `public/css` y `public/js`; no se necesita una compilación de npm, Mix ni Tailwind.
- Bootstrap, Bootstrap Icons, Leaflet y DataTables se cargan desde sus CDN en las vistas que los requieren. jQuery se carga únicamente junto con DataTables.

## Validación

```sh
php artisan test
node --test tests/Frontend/*.test.cjs
php artisan view:cache
php artisan view:clear
```

Las pruebas JavaScript usan el ejecutor integrado de Node y no requieren paquetes npm. Comprueban los filtros de tablas y tarjetas y las respuestas fuera de orden del selector de municipios.

## Código principal

- `routes/web.php`: rutas públicas y administrativas; las páginas estáticas usan `Route::view`.
- `app/Services/RouteFinder.php`: rutas directas y conexiones compatibles con los horarios y paradas.
- `app/Http/Controllers/AutobusController.php`: formularios compartidos, validación, guardado de servicios y auditoría de recorridos.
- `public/css/style.css`: tema y componentes compartidos; consultar `docs/ui-design.md`.
- `public/js/list-filter.js`: índice de listas estáticas para filtrar sin recorrer el DOM en cada pulsación.
- `public/js/terminal-form.js`: carga de municipios por departamento sin dependencia de jQuery.

La configuración del acceso principal utiliza `ADMIN_USERNAME` y `ADMIN_PASSWORD_HASH`; el hash se prepara con el comando administrativo disponible en `php artisan list`. No guardar credenciales en Git.

Ver `docs/refactoring.md` para el alcance de la limpieza y la recuperación de archivos retirados.

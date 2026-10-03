# Limpieza y refactorización

Se revisaron referencias en aplicación, rutas, vistas, configuración, pruebas y scripts antes de retirar archivos. Las migraciones históricas, fotos, datos, respaldos de bases de datos y endpoints registrados se conservan.

## Archivos retirados

- Cuatro vistas sin llamadas desde rutas, controladores ni otras vistas: `welcome`, `buscar.buscar`, `autobus.verbus` y `departamentos.verdepartamento`.
- Trait `Recursos`, sin clases consumidoras.
- Controladores `IndexController`, `AnunciosController` y `EnlacesController`: sus rutas ahora renderizan directamente las mismas vistas. El método sin uso de `EnlacesController` referenciaba un modelo inexistente.
- Archivos vacíos `public/js/peticiones.js` y `debug_toast.php`.
- Paquete local jQuery UI sin referencias desde el código activo.
- Fuentes, bundles, manifiesto y configuración de Mix/Tailwind sin consumidores en las vistas. También se retiraron los manifiestos npm de esa compilación abandonada; las bibliotecas utilizadas actualmente continúan cargándose desde CDN.
- `public/css/system-ui.css`: integrado en `style.css`, con una sola definición de tokens. Se retiraron 139 declaraciones anteriores reemplazadas por reglas posteriores del mismo selector.
- Prueba unitaria de ejemplo que solo comprobaba `true === true`, sin ejercitar el sistema.
- Hooks de toast sin consumidores y sincronización de campos ocultos del formulario de buses. Los horarios y tarifas ahora se envían desde las paradas; el servidor conserva la entrada anterior para compatibilidad.

## Mejoras funcionales

- `/ruta`, que antes intentaba renderizar una vista inexistente, redirige al catálogo de terminales manteniendo su nombre y protección administrativa.
- Se centralizaron los datos de los formularios de buses y se eliminó la consulta de servicios pendientes cuyo resultado nunca se utilizaba.
- La búsqueda conserva la resolución por ID y por nombre y evita dos consultas redundantes de existencia de municipios.
- El autocompletado carga únicamente las columnas que necesita.
- Editar datos de un bus con las mismas paradas mantiene sus IDs y fechas y evita recrear el recorrido. Los cambios reales de terminales y paradas continúan auditándose dentro de la transacción.
- Los filtros indexan una vez las filas o tarjetas estáticas; las siguientes pulsaciones comparan texto en memoria.
- El formulario de terminales usa JavaScript nativo y descarta respuestas de departamentos que ya no están seleccionados.
- El comportamiento del mapa se sirve desde `public/js/route-map.js`; solo los datos del itinerario permanecen en el HTML.
- Los estilos de horarios se cargan desde `public/css/terminal-schedules.css`, sin repetirlos en el cuerpo del documento.

## Recuperación

Antes de eliminar se guardó una copia y se verificó que cada archivo coincidiera con su copia:

- `.deploy-artifacts/refactor-unused-backup.zip`: 41 archivos, 4 686 531 bytes originales.
- `.deploy-artifacts/refactor-unused-manifest.json`: lista exacta y comprobaciones de referencias.
- `.deploy-artifacts/refactor-css-backup.zip`: estilos previos a la consolidación.

Estas copias locales están excluidas de Git. Para recuperar un archivo concreto, extraer solo su entrada del ZIP a su ruta original. El historial Git también conserva los archivos que estaban versionados.

## Validación

Las pruebas PHP cubren rutas, búsqueda, catálogos, paradas, tarifas, auditoría, sesiones y permisos. Se añadieron regresiones para la resolución por nombre, municipios inválidos, la URL antigua y la conservación y modificación del recorrido. Las pruebas de Node cubren el índice del filtro, respuestas AJAX fuera de orden y errores de carga.

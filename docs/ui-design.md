# Interfaz de Terminales Nicaragua

La interfaz usa la orientación Minimalism & Swiss Style de `ui-ux-pro-max`, adaptada al azul existente del proyecto. Prima la lectura de horarios, recorridos y formularios, con tipografía sans serif y controles visibles.

- `public/css/style.css` contiene los tokens y componentes compartidos. Se carga antes de los estilos de cada vista. La paleta se define en un solo bloque `:root`.
- Colores: texto `#262b40`, secundario `#52627a`, acción `#0465aa`, acción oscura `#06457f`, fondo `#f5f7fb`, superficie blanca. Éxito y eliminación conservan sus colores semánticos.
- Tipografía: DM Sans con alternativa del sistema; texto base de 16 px. Usar encabezados secuenciales y textos secundarios legibles.
- Botones y campos: altura mínima de 44 px, foco visible, nombres accesibles y separación entre acciones. Los iconos junto al texto son decorativos.
- Las tablas anchas desplazan dentro de su contenedor. Los formularios y tarjetas se apilan en móvil. Las animaciones respetan la preferencia de movimiento reducido.
- La navegación administrativa se renderiza una sola vez desde el layout en rutas protegidas con sesión autenticada. Las páginas públicas no muestran controles administrativos.
- El autocompletado acepta flechas, Enter y Escape y descarta respuestas anteriores cuando cambia el texto.
- Contacto utiliza las acciones disponibles de búsqueda y sugerencias. No publicar correos, precios ni formularios de envío de ejemplo.

Para revisar cambios: ejecutar `php artisan test` y comprobar inicio, búsqueda, catálogos y formularios a 375 px, 768 px y escritorio. Comprobar selección de municipios, selección del mapa, filtros de listas y navegación con teclado.

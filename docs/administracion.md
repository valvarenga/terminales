# Administración de Terminales

## Activación

Requiere las dependencias PHP instaladas, la base de datos disponible y un respaldo previo.

```sh
php artisan terminales:backup
php artisan migrate --force
php artisan view:clear
```

En XAMPP, si `mysqldump` no está en PATH, configura `BACKUP_MYSQLDUMP=C:\xampp\mysql\bin\mysqldump.exe` en el entorno. Las migraciones nuevas agregan tarifas, revisión de sugerencias, roles, historial y estadísticas; no inventan tarifas ni consultas anteriores. Los registros existentes conservan sus datos. Las migraciones antiguas de imágenes ahora también funcionan con SQLite.

Acceso privado: `/admin/login`. Esta dirección no se muestra ni se enlaza en la navegación pública, incluso cuando hay una sesión administrativa activa; debe ser conocida y utilizada únicamente por administradores autorizados. Se conserva el administrador configurado mediante `ADMIN_USERNAME` y `ADMIN_PASSWORD_HASH`. Desde **Usuarios y permisos** puede crear cuentas con correo y contraseña de al menos 12 caracteres, letras y números. Las cuentas antiguas sin rol administrativo no reciben acceso automáticamente.

## Funciones

- **Tarifas:** importe C$ por pasajero en alta y edición de autobuses. Vacío significa desconocido, cero gratuito. El total del itinerario solo aparece si se conocen todas las tarifas; es estimado.
- **Recorridos:** cada autobús tiene una lista ordenada de municipios con hora de paso y tarifa acumulada desde el origen. El municipio de origen se limita al departamento de la terminal seleccionada; las paradas posteriores pueden pertenecer a otros departamentos. Los pasajeros pueden subir en cualquier parada y bajar en una posterior; la tarifa se obtiene restando ambos acumulados. El mapa usa las coordenadas de los municipios como ayuda visual, pero nunca agrega ni reordena paradas automáticamente.
- **Edición:** autobuses permiten cambiar nombre, placa, categoría, horas, origen, destino, terminal y tarifa. Departamentos, municipios y terminales admiten reemplazar o quitar fotografías. Se conservan archivos previos y URLs de los registros. Los cambios geográficos incompatibles se rechazan; renombrar municipios actualiza los textos de los servicios asociados.
- **Sugerencias:** filtrar pendientes/aprobadas/rechazadas, vincular a terminal y registrar revisión. La foto solo se publica si se selecciona la casilla; reemplazar una foto existente requiere selección adicional. Cada sugerencia se revisa una vez; rechazo requiere motivo.
- **Estadísticas:** consultas de los últimos 7, 30 o 90 días, trayectos más buscados, consultas sin resultados y servicios sin tarifa o municipios vinculados. No se guardan identidades ni IP. Se omiten recargas consecutivas de una misma consulta durante 60 segundos. No representan personas únicas ni horarios verificados.
- **Historial:** responsable, fecha, registro y valores anteriores/nuevos en creación, edición, eliminación y revisión. Las contraseñas y tokens se excluyen. El cambio y su registro de auditoría se guardan juntos. No hay interfaz para alterar el historial. No reconstruye acciones anteriores a la actualización ni registra SQL externo.
- **Permisos:** editor registra/edita/revisa; administrador además elimina, gestiona cuentas y consulta historial. Cambiar acceso o contraseña invalida sesiones anteriores. No se permite que un administrador se desactive o quite su propio permiso.

## Respaldo y operación

El programador Laravel tiene `terminales:backup` a las 02:00 en la zona horaria de la aplicación, sin ejecuciones solapadas. Para automatizarlo el servidor debe ejecutar `php artisan schedule:run` cada minuto. Este cambio no instala cron ni una tarea de Windows. Consulta [backups.md](backups.md) para requisitos, restauración y limitaciones. No se elimina automáticamente ningún respaldo: supervisar espacio y conservar una copia privada fuera del servidor.

## Validación

```sh
php vendor/phpunit/phpunit/phpunit
php artisan view:cache
```

Las pruebas usan SQLite aislado y cubren tarifas, edición, revisión de fotos, permisos, historial, estadísticas y restauración de respaldo. No ejecutar pruebas sobre una base de producción.

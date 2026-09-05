# Respaldos locales

Ejecutar desde la carpeta del proyecto:

```sh
php artisan terminales:backup
```

El comando genera un ZIP con nombre y sufijo aleatorio en `storage/app/backups`. Incluye una copia consistente de SQLite o un volcado SQL de MySQL/MariaDB, archivos de `storage/app/public`, fotos privadas de `storage/app/sugerencias-terminales` y un manifiesto con SHA-256 de la base. No incluye .env, archivos ocultos, enlaces simbolicos, logs, sesiones ni respaldos anteriores. No cambia datos de la aplicacion.

Requisitos: PHP Phar habilitado (no necesita extension ZipArchive); SQLite con soporte VACUUM INTO, o `mysqldump` / `mariadb-dump` compatible en PATH. Para indicar su ruta, configurar `BACKUP_MYSQLDUMP` en el entorno, por ejemplo `C:\xampp\mysql\bin\mysqldump.exe`. El limite de ejecucion del volcado es 300 segundos, configurable en `config/backups.php`.

MySQL utiliza un archivo temporal de opciones dentro de una carpeta privada. Usuario y clave no se incluyen en argumentos del proceso ni mensajes. El archivo se elimina al terminar, incluso si ocurre un error. En Unix se solicitan permisos 0700 para carpetas y 0600 para archivos; en Windows se deben mantener ACL privadas para la cuenta que ejecuta PHP. No publicar `storage/app`: el enlace web debe apuntar solamente a `storage/app/public`.

El ZIP contiene datos y fotos privadas y no esta cifrado. Guardarlo con acceso restringido. No hay rotacion automatica: vigilar espacio libre y conservar copias fuera del mismo disco mediante el procedimiento de almacenamiento privado del operador. Una copia en el mismo servidor no cubre la perdida de ese servidor.

## Programacion

La tarea está integrada en el programador Laravel:

```php
$schedule->command('terminales:backup')->dailyAt('02:00')->withoutOverlapping();
```

Requiere que el servidor ejecute `php artisan schedule:run` cada minuto. Este trabajo no configura cron ni tareas externas. El horario corresponde a la zona horaria de Laravel. Antes de activar el disparador externo, verificar espacio, permisos y el primer respaldo.

## Restauracion de prueba

Siempre restaurar primero en una carpeta y base aisladas, con la misma version de aplicacion. No sobrescribir una base activa para comprobar un respaldo.

1. Abrir el ZIP y revisar `manifest.json`.
2. Extraer `database.sqlite` o `database.sql` y comprobar que su SHA-256 coincide con `database_sha256` del manifiesto.
3. SQLite: abrir el archivo extraido con PDO SQLite o configurar una instancia aislada para usarlo. MySQL/MariaDB: importar el SQL en un servidor de prueba con el cliente correspondiente; el dump incluye CREATE DATABASE/USE del nombre original, por eso el servidor de prueba debe estar separado de produccion. Introducir credenciales por prompt o un archivo privado, nunca dentro del comando.
4. Extraer `storage/app/public` y `storage/app/sugerencias-terminales` en la instancia de prueba. Verificar filas, tarifas, horarios y lectura de fotos publicas/privadas.
5. Una restauracion real requiere detener escrituras antes de sustituir datos y fotos. El snapshot de base y las fotos se capturan secuencialmente: si se necesita coincidencia exacta, pausar cambios administrativos durante el respaldo. `--single-transaction` garantiza consistencia de tablas transaccionales MySQL, no de tablas MyISAM ni cambios DDL simultaneos.

SQLite se verifico con `php artisan test --filter=LocalBackupTest`: creacion de ZIP, restauracion real mediante PDO, comparacion de datos y hash, fotos, exclusion de secretos y respaldo previo, limpieza y errores redactados. MySQL/MariaDB requiere una prueba real de respaldo/restauracion en su entorno antes de confiar en su copia; no se ha ejecutado contra produccion.

#!/usr/bin/env bash
set -euo pipefail

: "${ADMIN_USERNAME:?Set ADMIN_USERNAME before running this script}"
: "${ADMIN_PASSWORD:?Set ADMIN_PASSWORD before running this script}"

site_dir=/var/www/terminales
db_name=terminales_nicaragua
db_user=terminales_app
db_password=$(openssl rand -hex 24)
admin_hash=$(php -r "echo password_hash(getenv('ADMIN_PASSWORD'), PASSWORD_BCRYPT);")

sudo mkdir -p "$site_dir"
sudo tar -xzf /tmp/terminales-deploy.tar.gz -C "$site_dir"
sudo chown -R opc:nginx "$site_dir"
sudo find "$site_dir" -type d -exec chmod 775 {} \;
sudo find "$site_dir" -type f -exec chmod 664 {} \;
sudo chmod 775 "$site_dir/storage" "$site_dir/bootstrap/cache"

cd "$site_dir"
cp .env.example .env
rm -f bootstrap/cache/*.php
composer install --no-dev --optimize-autoloader --no-interaction

sudo mysql -e "CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS '$db_user'@'localhost' IDENTIFIED BY '$db_password';"
sudo mysql -e "ALTER USER '$db_user'@'localhost' IDENTIFIED BY '$db_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON $db_name.* TO '$db_user'@'localhost'; FLUSH PRIVILEGES;"

sed -i \
  -e "s|^APP_URL=.*|APP_URL=http://150.136.166.198|" \
  -e "s|^DB_DATABASE=.*|DB_DATABASE=$db_name|" \
  -e "s|^DB_USERNAME=.*|DB_USERNAME=$db_user|" \
  -e "s|^DB_PASSWORD=.*|DB_PASSWORD=$db_password|" \
  -e "s|^ADMIN_USERNAME=.*|ADMIN_USERNAME=$ADMIN_USERNAME|" \
  -e "s|^ADMIN_PASSWORD_HASH=.*|ADMIN_PASSWORD_HASH=$admin_hash|" \
  .env

php artisan key:generate --force
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

sudo tee /etc/nginx/conf.d/terminales.conf >/dev/null <<'NGINX'
server {
    listen 80;
    server_name _;
    root /var/www/terminales/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php-fpm/www.sock;
    }

    location ~ /\. { deny all; }
}
NGINX

sudo rm -f /etc/nginx/conf.d/default.conf
sudo nginx -t
sudo systemctl restart nginx php-fpm

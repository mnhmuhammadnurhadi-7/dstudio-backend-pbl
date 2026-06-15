#!/usr/bin/env bash
set -e

# If AIVEN_CA secret provided, write it to the container and set MYSQL_ATTR_SSL_CA
if [ -n "$AIVEN_CA" ]; then
  mkdir -p /etc/ssl/certs
  echo "$AIVEN_CA" > /etc/ssl/certs/aiven-ca.pem
  export MYSQL_ATTR_SSL_CA=/etc/ssl/certs/aiven-ca.pem
fi

# Ensure composer dependencies are present
if [ ! -d vendor ]; then
  composer install --no-dev --prefer-dist --optimize-autoloader
fi

# Ensure app key and basic setup
php artisan key:generate --force || true
php artisan migrate --force || true
php artisan storage:link || true

# Start PHP built-in server (Render sets $PORT)
exec php -S 0.0.0.0:"${PORT:-10000}" -t public

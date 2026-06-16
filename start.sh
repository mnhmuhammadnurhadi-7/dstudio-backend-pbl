#!/usr/bin/env bash
set -e

# If AIVEN_CA secret provided, write it to the container and set MYSQL_ATTR_SSL_CA
if [ -n "$AIVEN_CA" ]; then
  mkdir -p /etc/ssl/certs
  # Use PHP to properly handle newlines just in case they were flattened by the environment variables UI
  # It preserves the BEGIN and END tags, but replaces spaces in the base64 body with newlines
  php -r '$c = getenv("AIVEN_CA"); $c = str_replace(["-----BEGIN CERTIFICATE-----", "-----END CERTIFICATE-----"], ["_BEGIN_", "_END_"], $c); $c = str_replace([" ", "\n", "\r", "\t", "\\n"], "\n", $c); $c = str_replace(["_BEGIN_", "_END_"], ["-----BEGIN CERTIFICATE-----", "-----END CERTIFICATE-----"], $c); file_put_contents("/etc/ssl/certs/aiven-ca.pem", trim($c) . "\n");'
  
  # Remove blank lines and fix the formatting for OpenSSL
  awk 'NF' /etc/ssl/certs/aiven-ca.pem > /etc/ssl/certs/aiven-ca-clean.pem
  mv /etc/ssl/certs/aiven-ca-clean.pem /etc/ssl/certs/aiven-ca.pem
  
  export MYSQL_ATTR_SSL_CA=/etc/ssl/certs/aiven-ca.pem
fi

# Ensure composer dependencies are present
if [ ! -d vendor ]; then
  composer install --no-dev --prefer-dist --optimize-autoloader
fi

# Ensure basic setup
php artisan migrate --force || true
php artisan storage:link || true

# Start PHP built-in server (Render sets $PORT)
exec php -S 0.0.0.0:"${PORT:-10000}" -t public

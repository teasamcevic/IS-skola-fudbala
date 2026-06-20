#!/bin/bash
set -e

# Ne čistimo aplikacioni cache pre nego što baza postane dostupna.
php artisan config:clear
php artisan route:clear
php artisan view:clear

for attempt in {1..12}; do
    if php artisan migrate --force; then
        break
    fi

    if [ "$attempt" -eq 12 ]; then
        echo "Database migrations failed after $attempt attempts."
        exit 1
    fi

    echo "Database is not ready yet. Retrying in 5 seconds..."
    sleep 5
done

if [ "${RUN_SEEDER:-false}" = "true" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

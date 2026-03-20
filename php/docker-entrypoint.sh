#!/bin/sh

if [ ! -d /var/www/html/vendor ]; then
    echo ">>> Installing Composer dependencies..."
    cd /var/www/html && composer install --no-interaction --no-dev --prefer-dist
    echo ">>> Done."
fi

exec "$@"
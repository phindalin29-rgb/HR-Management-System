#!/bin/bash

# Run migrations, seeders, and storage link
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link --force

# Execute the main container command (Apache)
exec "$@"
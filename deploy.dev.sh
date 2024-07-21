#!/bin/sh

cp .env.example .env

if [ "$(uname)" = "Darwin" ]; then
    # macOS (BSD sed)
    sed -i '' 's/DB_CONNECTION=pgsql/DB_CONNECTION=sqlite/g' .env
    sed -i '' 's/DB_HOST=127.0.0.1/#DB_HOST=127.0.0.1/g' .env
    sed -i '' 's/DB_PORT=5432/#DB_PORT=5432/g' .env
    sed -i '' 's/DB_DATABASE=laravel_api/#DB_DATABASE=laravel_api/g' .env
    sed -i '' 's/DB_USERNAME=root/#DB_USERNAME=root/g' .env
    sed -i '' 's/DB_PASSWORD=/#DB_PASSWORD=/g' .env
else
    # Linux (GNU sed)
    sed -i 's/DB_CONNECTION=pgsql/DB_CONNECTION=sqlite/g' .env
    sed -i 's/DB_HOST=127.0.0.1/#DB_HOST=127.0.0.1/g' .env
    sed -i 's/DB_PORT=5432/#DB_PORT=5432/g' .env
    sed -i 's/DB_DATABASE=laravel_api/#DB_DATABASE=laravel_api/g' .env
    sed -i 's/DB_USERNAME=root/#DB_USERNAME=root/g' .env
    sed -i 's/DB_PASSWORD=/#DB_PASSWORD=/g' .env
fi

php artisan key:generate

php artisan migrate:fresh --seed --force

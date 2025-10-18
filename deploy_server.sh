#!/bin/bash

set -e

git pull origin master

php8.3 composer.phar install --no-dev --optimize-autoloader

php8.3 artisan cache:clear        # Очистить общий кэш
php8.3 artisan config:clear       # Очистить кеш конфигураций
php8.3 artisan route:clear        # Очистить кеш маршрутов
php8.3 artisan view:clear         # Очистить кеш Blade шаблонов
php8.3 artisan event:clear        # Очистить кеш событий (если используется)


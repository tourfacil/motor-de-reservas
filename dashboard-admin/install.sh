#!/usr/bin/env bash

echo Uploading Application container
docker-compose up -d --build

echo Restarting Web Container
docker-compose up -d web

echo Install dependencies
docker exec -it tourfacil_admin_php composer update --optimize-autoloader --no-dev

echo Generate key
docker exec -it tourfacil_admin_php php artisan key:generate

#echo Make migrations
#docker exec -it tourfacil_admin_php php artisan migrate

echo Giving permissions on folders app
docker exec -it tourfacil_admin_php chmod -R 777 storage

echo Init supervisor
docker exec -it tourfacil_admin_php service supervisor start

echo Start Cron
docker exec -it tourfacil_admin_php service cron start

echo Information of new containers
docker ps -a

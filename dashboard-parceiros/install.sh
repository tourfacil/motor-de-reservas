#!/usr/bin/env bash

echo Uploading Application container
docker-compose up -d --build

echo Restarting Web Container
docker-compose up -d web

echo Install dependencies
docker exec -it fornecedores_php composer install --optimize-autoloader --no-dev

echo Giving permissions on folders app
docker exec -it fornecedores_php chmod -R 777 storage

echo Init supervisor
docker exec -it fornecedores_php service supervisor start

echo Information of new containers
docker ps -a

#!/usr/bin/env bash

echo Uploading Application container
docker-compose up -d

echo Giving permissions on folders app
docker exec -it fornecedores_php chmod -R o+rw bootstrap storage

echo Init supervisor
docker exec -it fornecedores_php service supervisor start

echo Information of new containers
docker ps -a

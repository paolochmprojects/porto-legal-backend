#!/bin/bash

echo "Construyendo contenedor php8.3-cli..."

docker build -t php8.3env -f ./DockerfilePHPinit .

echo "Contenedor php8.3-cli construido"

echo "Instalando dependencias con composer..."

docker run --rm -v .:/app php8.3env composer install

echo "Dependencias instaladas"

echo "Eliminando contenedor php8.3-cli..."

docker rmi php8.3env
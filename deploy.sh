#!/bin/bash 

./installer.sh

cp .env.example .env

# Levantar el stack de contenedores
./vendor/bin/sail up -d 

# Generar llaves
echo "Generando llave..."
./vendor/bin/sail php artisan key:generate
echo "Llave generadas"

# Crear token
echo "Creando token..."
./vendor/bin/sail php artisan jwt:secret -f
echo "Token creado"


echo "Iniciando migraciones..."
until ./vendor/bin/sail php artisan migrate; do
    echo "Intentando ejecutar migraciones..."
    sleep 3
done
echo "Migraciones ejecutadas..."


echo "Iniciando servidor..."
./vendor/bin/sail exec -d laravel.test php artisan serve
echo "Servidor iniciado"

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Laravel

## Descripción

El proyecto fue desarrollado sobre la imagen oficial de php en docker hub [php:8.3-cli](https://hub.docker.com/layers/library/php/8.3-cli/images/sha256-fa9d0d6d4def5fb95c76df2378589a76f3056728bab022035969cfe19f55b7f8?context=explore) y laravel en su version 11 acompañado de una base de datos MySQL, también obtenida de pagina oficial de docker hub [mysql:9.0.0](https://hub.docker.com/layers/library/mysql/9.0.0/images/sha256-856aa5f8c4d6fc5b0c27ffa97d308343c323c9ec2e3d25d80401c2f595e9bb4d?context=explore)

### Instrucciones para correr la aplicación en desarrollo (Docker)

Se debe ejecutar el siguiente comando en el directorio del proyecto. En la bandera -p se indica el nombre del stack que se creará puede ser el mismo que el nombre del proyecto u otro. La bandera -f se indica el archivo de docker-compose. la bandera -d se indica que se inicia el contenedor en segundo plano.

```bash
docker compose -f .devcontainer/docker-compose.yml -p porto-legal up -d
```

Una vez ejecutado el comando de arriba, dependiendo de la configuración de los permisos del directorio de la aplicación, puede ser que no permita ser modificado se puede solucionar con chmod.

```bash
chmod -R 777 .
```

para el desarrollo fuera del contenedor, solo se debe posicionar en el directorio del proyecto y modificar lo necesario. tomado en cuenta que para ejecutar las migraciones y otras operaciones se debe anteponer docker exec y el nombre del contenedor.

```bash
docker exec porto-legal-backend-1 php artisan migrate
```

o en todo caso usar el la extención devcontainer de vscode y realizar las operaciones dentro del contenedor sin anteponer los comandos.

### Correr las migraciones

Se debe ejecutar.

```bash
docker exec porto-legal-backend-1 php artisan migrate
```
o dentro del contenedor

```bash
php artisan migrate
```

### Correr la aplicación

Se debe ejecutar el comando habitual de artisan serve con la bandera --host 0.0.0.0 para que se exponga correctamente al aplicación.

```bash
docker exec porto-legal-backend-1 php artisan serve --host 0.0.0.0
```

o dentro del contenedor con la misma bandera.

```bash
php artisan serve --host 0.0.0.0
```

para el resto de comandos es la misma expresión anteponer el comando docker exec y el nombre del contenedor o tambien se puede utilizar.

```bash
docker exec -it porto-legal-backend-1 /bin/bash
```
esto permitirá interactuar con el contenedor desde la terminal y ejecutar los comando de php y composer en el contenedor.

## Documentación

Para probar los endpoints en la raiz del proyecto hay un JSON para insomnia.

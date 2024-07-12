<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Porto Legal Backend

## Descripción

El proyecto fue desarrollado sobre la configuración entrega por Sail [ver documentación](https://laravel.com/docs/11.x/sail) para laravel con docker, por lo cual se ha generado un archivo docker compose para el despliegue en contenedores, con Mysql como base de datos. 

### Instrucciones para correr la aplicación en desarrollo (Docker)

Se debe ejecutar el siguiente comando en el directorio del proyecto. En la bandera -d se indica que se debe ejecutar el stack de contenedores en segundo plano, y el nombre del stack se obtendra del directorio. El nombre de cada contenedor se concadenará con el nombre del directorio y el nombre del servicio dor defecto, por lo cual será tomado por el nombre del directorio, en este caso porto-legal-backend.service.

```bash
./vendor/bin/sail up -d
```

Una vez ejecutado el comando de arriba, dependiendo de la configuración de los permisos del directorio de la aplicación, puede ser que no permita ser modificado se puede solucionar con chmod.

```bash
chmod -R 777 .
```

para el desarrollo fuera del contenedor, solo se debe posicionar en el directorio del proyecto y modificar lo necesario. tomado en cuenta que para ejecutar las migraciones y otras operaciones se debe anteponer ./vendor/bin/sail y el comando, desde el directorio del proyecto.

```bash
./vendor/bin/sail composer install

./vendor/bin/sail php artisan migrate

./vendor/bin/sail php artisan serve
```

o en todo caso usar el la extensión devcontainer de vscode y realizar las operaciones dentro del contenedor sin anteponer los comandos desde la terminal del contenedor.

### Correr las migraciones

Se debe ejecutar.

```bash
./vendor/bin/sail php artisan migrate
```
o dentro del contenedor

```bash
php artisan migrate
```

### Correr la aplicación

Se debe ejecutar el comando habitual de artisan serve con la bandera --host 0.0.0.0 para que se exponga correctamente al aplicación.

```bash
./vendor/bin/sail php artisan serve
```

o dentro del contenedor con la misma bandera.

```bash
php artisan serve
```

para el resto de comandos es la misma expresión anteponer el comando ./vendor/bin/sail o tambien se puede utilizar.

```bash
docker exec -it porto-legal-backend-laravel.test-1 /bin/bash
```
esto permitirá interactuar con la terminal del contenedor desde la terminal del host y ejecutar los comandos de php y composer en el contenedor directamente.

## Documentación

Para probar los endpoints en la raiz del proyecto hay un JSON para insomnia o levantar el proyecto en el navegador y navegar [http://localhost/api/documentation](http://localhost/api/documentation) para ver la documentación en swagger.

## Despliegue

Para realizar el despliegue en una VPS, se requiere tener docker y docker compose instalados y cambiar la variable APP_DEBUG en el archivo .env a false, para que se pueda realizar el despliegue correctamente, con los mismos pasos anteriores, si se requiere adicionar mas servicios en el mismo servidor se puede realizar con el siguiente comando.

```bash
./vendor/bin/sail php artisan sail:add
```

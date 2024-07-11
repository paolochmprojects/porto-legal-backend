FROM php:8.3-cli

RUN apt update && apt upgrade -y && \ 
    apt install -y git unzip zip  

RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php \ 
    && mv composer.phar /usr/local/bin/composer 

WORKDIR /app

EXPOSE 8000

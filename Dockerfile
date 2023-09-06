FROM php:8.2-apache-buster

#Atualiza o linux e instala pacotes necessários do projeto
RUN apt update -y && apt upgrade -y
RUN apt install -y  --no-install-recommends git zip

#Define local de trabalho
WORKDIR /var/www/html

#Link Container/Local
COPY --chown=www-data:www-data src /var/www/html

#Cria arquivo .env, requisitado pelo symfony
RUN touch /var/www/html/.env

#Composer
RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

#Executa PHP e expõe na porta 8080
RUN php
EXPOSE 8080

FROM php:8.2-apache-buster

#Atualiza o linux e instala pacotes necessários do projeto
RUN apt update -y && apt upgrade -y
#GIT
RUN apt install -y  --no-install-recommends git zip
#Zip
RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip
RUN apt install unzip
#Postgres
RUN apt install -y libxml2-dev postgresql-client libpq-dev
RUN docker-php-ext-install pdo_pgsql pgsql

#Define local de trabalho
WORKDIR /var/www/html

#Link Container/Local
COPY --chown=www-data:www-data src /var/www/html

#Cria arquivo .env, requisitado pelo symfony
RUN touch /var/www/html/.env

#instala o composer e atualiza/instala as dependências/pacotes
RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN composer install --no-ansi --no-interaction --no-progress --no-scripts --optimize-autoloader --no-dev

#Define configurações do apache
COPY ./apache-configs/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN sed 's|Listen 80|Listen 8080|' -i /etc/apache2/ports.conf

#Executa PHP e expõe na porta 8080
RUN php
EXPOSE 8080

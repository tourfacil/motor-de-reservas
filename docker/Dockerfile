# PHP 7.3.11 de FPM STRETCH
FROM php:7.3.11-fpm-stretch

# PASTA DO PROJETO
WORKDIR /var/www

# SCRIPT DE CONFIGURAÇÃO
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Instalação das extensões do PHP
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql zip exif pcntl gd memcached

# Instalação das dependencias do servidor
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    cron \
    wget \
    libxrender1 \
    libxrender-dev \
    libfontconfig \
    lua-zlib-dev \
    libmemcached-dev \
    nginx

# Instalação do Supervisor
RUN apt-get install -y supervisor

# Instalação do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Limpeza de cache do APT
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Adicionado usuário para o Laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy nginx/php/supervisor configs
# RUN cp docker/supervisor.conf /etc/supervisord.conf
# RUN cp docker/php.ini /usr/local/etc/php/conf.d/app.ini
# RUN cp docker/nginx.conf /etc/nginx/sites-enabled/default

# Criação de pastas e arquivos para registrar erros do PHP
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Instalação do WKHTMLTOPDF -> Usado para gerar PDFs a partir de páginas WEB
RUN wget "https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz" && \
    tar -xvf wkhtmltox-0.12.4_linux-generic-amd64.tar.xz && \
    cd wkhtmltox/bin/ && \
    mv wkhtmltopdf /usr/bin/wkhtmltopdf && \
    mv wkhtmltoimage /usr/bin/wkhtmltoimage && \
    chmod a+x /usr/bin/wkhtmltopdf && \
    chmod a+x /usr/bin/wkhtmltoimage

RUN echo 'xvfb-run -a -s "-screen 0 1024x768x24" wkhtmltopdf "$@"' > /usr/bin/wkhtmltopdf.sh && \
    chmod a+x /usr/bin/wkhtmltopdf.sh

EXPOSE 80
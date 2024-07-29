FROM php:7.3.11-fpm-stretch

# Install PHP extensions
RUN apt-get update && \
    apt-get install -y -qq git \
        libjpeg62-turbo-dev \
        apt-transport-https \
        libfreetype6-dev \
        build-essential \
        libmcrypt-dev \
        libpng-dev \
        supervisor \
        zip unzip \
        rsyslog \
        libxrender1 \
        libxrender-dev \
        gdebi \
        wget \
        xvfb \
        libfontconfig \
        libssl1.0-dev \
        cron \
        libzip-dev

# extension GD image
RUN docker-php-ext-configure \
        gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

RUN docker-php-ext-install \
        -j$(nproc) iconv \
        pdo_mysql \
        mbstring \
        bcmath \
        zip \
        pdo \
        gd \
        pcntl \
        exif

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Setup cron job
# RUN (crontab -l ; echo "* * * * * cd /var/www/app && /usr/local/bin/php artisan schedule:run >> /var/log/cron.log 2>&1") | crontab
RUN (crontab -l ; echo "* * * * * cd /var/www/app && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1") | crontab

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN wget "https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz" && \
    tar -xvf wkhtmltox-0.12.4_linux-generic-amd64.tar.xz && \
    cd wkhtmltox/bin/ && \
    mv wkhtmltopdf /usr/bin/wkhtmltopdf && \
    mv wkhtmltoimage /usr/bin/wkhtmltoimage && \
    chmod a+x /usr/bin/wkhtmltopdf && \
    chmod a+x /usr/bin/wkhtmltoimage

RUN echo 'xvfb-run -a -s "-screen 0 1024x768x24" wkhtmltopdf "$@"' > /usr/bin/wkhtmltopdf.sh && \
    chmod a+x /usr/bin/wkhtmltopdf.sh

WORKDIR /var/www/app

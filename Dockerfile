FROM php:5.6.13-apache

MAINTAINER Diego Weinstein <diegow@netlabs.com.ar>

############ from netlabs/laravel dockerfile ##############

# point default site to public directory
RUN sed -i 's/www\/html/www\/html\/public/g' /etc/apache2/apache2.conf

# install composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --filename=composer --install-dir=/usr/local/bin

# install missing extensions (and locales)
RUN apt-get update && \
        apt-get install -y zlib1g-dev libicu-dev locales
RUN docker-php-ext-install mbstring zip pdo_mysql

# Generate locale for es_AR
RUN echo "es_AR.UTF-8 UTF-8" >> /etc/locale.gen 
RUN locale-gen

# enable apache modules
RUN a2enmod rewrite

# activate php error logs
RUN echo php_flag log_errors On > /etc/apache2/conf-enabled/php-log-errors.conf

#clean up
RUN apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ADD scripts/* /

COPY configs/apache2/apache2.conf /etc/apache2/apache2.conf

WORKDIR /var/www/html

ADD src /var/www/html

# Set file owner
RUN chown -R www-data:www-data /var/www/html
# install vendors

#RUN composer install

RUN composer install --no-dev --no-interaction -o --no-ansi

EXPOSE 80

CMD /start.sh

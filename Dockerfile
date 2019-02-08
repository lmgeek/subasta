FROM php:5.6.13-apache

ARG STAGE

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

RUN  bash -c "if [ \"$STAGE\" == \"dev\" ] || [ \"$STAGE\" == \"test\" ]; \
     then \
       apt-get install -y php5-xdebug; \
       docker-php-ext-enable /usr/lib/php5/20131226/xdebug.so; \
     fi"

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

RUN bash -c "if [ \"$STAGE\" == \"production\" ] || [ \"$STAGE\" == \"\" ] || [ \"$STAGE\" == \"qa\" ]; \
    then \
      composer install --no-dev --no-interaction -o --no-ansi; \
      rm -rf /var/www/html/tests; \
    elif [ \"$STAGE\" == \"dev\" ] || [ \"$STAGE\" == \"test\" ]; \
    then \
      composer install --no-interaction -o --no-ansi; \
    fi"
      


EXPOSE 80

CMD /start.sh

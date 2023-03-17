FROM php:8.2-apache
RUN apt update && apt upgrade -y
RUN apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite && service apache2 restart

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

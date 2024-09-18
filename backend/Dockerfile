FROM yiisoftware/yii2-php:7.4-apache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/backend/web|g' /etc/apache2/sites-available/000-default.conf

RUN apt-get update && \
    apt-get install -y memcached libmemcached-dev zlib1g-dev && \
    pecl install memcached && \
    docker-php-ext-enable memcached && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN service apache2 restart






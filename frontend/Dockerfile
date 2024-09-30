FROM yiisoftware/yii2-php:7.4-apache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/frontend/web|g' /etc/apache2/sites-available/000-default.conf

# Install required packages including certbot
RUN apt-get update && \
    apt-get install -y memcached libmemcached-dev zlib1g-dev certbot python3-certbot-apache && \
    pecl install memcached && \
    docker-php-ext-enable memcached && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite ssl

# Set up HTTPS configuration
RUN sed -i '/<\/VirtualHost>/i \\\n    RewriteEngine On\n    RewriteCond %{HTTPS} off\n    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]' /etc/apache2/sites-available/000-default.conf

RUN service apache2 restart

# To obtain Let's Encrypt certificates
CMD ["sh", "-c", "service apache2 start && certbot --apache --non-interactive --agree-tos --email your-email@example.com -d your-domain.com && tail -f /dev/null"]

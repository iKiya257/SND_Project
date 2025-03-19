FROM php:8.2-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    git \
    unzip \
    libonig-dev
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install xml
RUN docker-php-ext-install dom
RUN docker-php-ext-install mbstring
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2enmod rewrite
WORKDIR /var/www/html
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN find /var/www/html -name "index.php" > /var/www/html/index_locations.txt

# เพิ่มการกำหนดค่า DirectoryIndex
RUN echo 'DirectoryIndex index.php index.html' > /etc/apache2/conf-available/directoryindex.conf
RUN a2enconf directoryindex

# กำหนด DocumentRoot เป็น /var/www/html/src
ENV APACHE_DOCUMENT_ROOT /src/SND_Project/index.php

# แก้ไข configuration ที่มีอยู่แล้วให้ใช้ APACHE_DOCUMENT_ROOT
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}/!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# เปิดใช้งาน AllowOverride และ Options
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/Options Indexes FollowSymLinks/Options Indexes FollowSymLinks MultiViews/' /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]
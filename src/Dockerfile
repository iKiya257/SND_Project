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

# เพิ่มการค้นหาและบันทึกข้อมูลเพื่อ debug
RUN find /var/www/html -type f -name "index.php" > /var/www/html/debug_paths.txt
RUN ls -la /var/www/html >> /var/www/html/debug_paths.txt
RUN ls -la /var/www/html/* 2>/dev/null >> /var/www/html/debug_paths.txt || true

# ตั้งค่า DocumentRoot เป็นโฟลเดอร์ที่เรารู้แน่นอนว่ามีอยู่
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# สร้างไฟล์ index.php ชั่วคราวในโฟลเดอร์หลักเพื่อแสดงข้อมูล debug
RUN echo '<?php\n\
echo "<h1>Debug Information</h1>";\n\
echo "<pre>";\n\
echo "Current directory: " . __DIR__ . "\\n";\n\
echo "PHP version: " . phpversion() . "\\n\\n";\n\
echo "Directory structure:\\n";\n\
system("find /var/www/html -type d | sort");\n\
echo "\\n\\nPHP files:\\n";\n\
system("find /var/www/html -name \"*.php\" | sort");\n\
echo "</pre>";\n\
?>' > /var/www/html/phpinfo.php

EXPOSE 80
CMD ["apache2-foreground"]
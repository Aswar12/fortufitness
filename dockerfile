# Dockerfile

# Gunakan image Node.js 16 sebagai dasar
FROM node:16

# Setel direktori kerja
WORKDIR /app

# Salin file project ke direktori kerja
COPY . /app/

# Instal dependencies
RUN npm install

# Jalankan npm run build
RUN npm run build

# Jalankan aplikasi
CMD ["npm", "start"]

# Konfigurasi Laravel
FROM php:8.3-apache

# Setel direktori kerja
WORKDIR /var/www/html

# Salin file project ke direktori kerja
COPY . /var/www/html/

# Instal dependencies
RUN apt-get update && apt-get install -y libzip-dev zip
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

# Konfigurasi Apache
RUN a2enmod rewrite

# Jalankan Apache
CMD ["apache2-foreground"]
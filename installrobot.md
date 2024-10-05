#!/bin/bash

# Token GitHub

TOKEN=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

# Repository GitHub

REPO=https://github.com/Aswar12/fortufitness.git

# Instalasi Git

sudo apt-get update
sudo apt-get install -y git
git config --global user.name "Nama Anda"
git config --global user.email "Email Anda"

# Instalasi PHP 8.3 dan ekstensi yang diperlukan

sudo apt-get install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring php8.3-zip php8.3-intl

# Instalasi MySQL

sudo apt-get install -y mysql-server
sudo service mysql start
sudo mysql -e "CREATE DATABASE fortufitness;"
sudo mysql -e "GRANT ALL PRIVILEGES ON fortufitness.\* TO 'root'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Instalasi Composer

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
composer config --global github-oauth.github.com $TOKEN

# Clone Project Laravel dari Git

git clone $REPO
cd fortufitness

# Instalasi Dependensi Laravel

composer install
cp .env.example .env
php artisan key:generate

# Konfigurasi Database Laravel

sed -i 's/DB_CONNECTION=._/DB_CONNECTION=mysql/' .env
sed -i 's/DB_HOST=._/DB_HOST=127.0.0.1/' .env
sed -i 's/DB_PORT=._/DB_PORT=3306/' .env
sed -i 's/DB_DATABASE=._/DB_DATABASE=fortufitness/' .env
sed -i 's/DB_USERNAME=._/DB_USERNAME=root/' .env
sed -i 's/DB_PASSWORD=._/DB_PASSWORD=/' .env

# Jalankan Migrasi Database Laravel

php artisan migrate
php artisan db:seed

# Instalasi Node.js

sudo apt-get install -y nodejs

# Instalasi npm

sudo npm install -g npm@latest

# Instalasi Dependensi npm

npm install

# Jalankan npm run dev

npm run dev

# Jalankan Server Laravel

php artisan serve

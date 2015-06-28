#!/usr/bin/env bash
# ################################################### #
# !  Frameworkless - Intial setup for Ubuntu 14.04  ! #
# ################################################### #

# Setup apititude (for HHVM)
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0x5a16e7281be7a449
sudo add-apt-repository 'deb http://dl.hhvm.com/ubuntu trusty main'
sudo apt-get update

# Install NGINX & HHVM
sudo apt-get install -y nginx hhvm
sudo update-rc.d hhvm defaults

# Install fastcgi for HHVM
sudo /usr/share/hhvm/install_fastcgi.sh

# Install MYSQL
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password $1'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password $1'
sudo apt-get -y install mysql-server
echo "FRAMEWORKLESS SETUP: For production use, please run mysql_secure_installation."

# Setup MYSQL permissions
mysql --user='root' --password='$1' --execute='CREATE DATABASE frameworkless;'
mysql --user='root' --password='$1' --execute='CREATE USER fwl_user@localhost;'
mysql --user='root' --password='$1' --execute='GRANT ALL ON frameworkless.* to fwl_user@localhost;'

# ############################# #
# ! Get things up and running ! #
# ############################# #

# Go to brickfinder install dir.
cd /opt/frameworkless

# Ensure proper permissions
chown -R www-data .

# Create tables and seed with default data
mysql --user='fwl_user' frameworkless < ./setup/database/frameworkless.sql
mysql --user='fwl_user' frameworkless < ./setup/database/seed.sql

# Download composer and install dependencies
curl -sS https://getcomposer.org/installer | php
hhvm composer.phar install

# Symlink the nginx config and restart nginx
sudo rm -f /etc/nginx/sites-available/*
sudo rm -f /etc/nginx/sites-enabled/*
sudo ln -s /opt/frameworkless/nginx.conf /etc/nginx/sites-enabled/frameworkless
sudo service nginx restart

echo "FRAMEWORKLESS SETUP: Complete!"

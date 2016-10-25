$script = <<'SCRIPT'
#!/usr/bin/env bash
# ################################################### #
# !  Frameworkless: Initial setup for Ubuntu 14.04  ! #
# ################################################### #

# Setup apt (for PHP 7)
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update

# Install NGINX & PHP 7
sudo apt-get install -y nginx php7.0-fpm

# Symlink the nginx config and restart nginx
sudo rm -f /etc/nginx/sites-available/*
sudo rm -f /etc/nginx/sites-enabled/*
sudo ln -s /opt/frameworkless/config/nginx/vagrant.conf /etc/nginx/sites-enabled/frameworkless
sudo service nginx restart
SCRIPT

Vagrant.configure(2) do |config|
    config.vm.box = "ubuntu/trusty64"
    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.network "forwarded_port", guest: 3306, host: 3307

    config.vm.synced_folder ".", "/vagrant", disabled: true
    config.vm.synced_folder ".", "/opt/frameworkless"

    config.ssh.insert_key = true

    config.vm.provision "shell", inline: $script
end

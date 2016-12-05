$script = <<'SCRIPT'
#!/usr/bin/env bash
# ################################################### #
# !  Frameworkless: Initial setup for Ubuntu 16.04  ! #
# ################################################### #

# Install NGINX & PHP 7
sudo apt-get update
sudo apt-get install -y nginx php7.0-fpm

# Symlink the nginx config and restart nginx
sudo rm -f /etc/nginx/sites-available/*
sudo rm -f /etc/nginx/sites-enabled/*
sudo ln -s /opt/frameworkless/misc/config/nginx/vagrant.conf /etc/nginx/sites-enabled/default
sudo systemctl restart nginx
SCRIPT

Vagrant.configure(2) do |config|
    config.vm.box = "ubuntu/xenial64"
    config.vm.network "forwarded_port", guest: 80, host: 8080

    config.vm.synced_folder ".", "/vagrant", disabled: true
    config.vm.synced_folder ".", "/opt/frameworkless"

    config.ssh.insert_key = true

    config.vm.provision "shell", inline: $script
end

#!/usr/bin/env bash
# Update the box, and insall all the necessary packages to support Laravel
apt-get -qq update
DEBIAN_FRONTEND=noninteractive apt-get install -qq -y libapache2-mod-php5 php5-cli php5-mysql php5-mcrypt php5-curl php-pear curl git

# Setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
  DocumentRoot "/vagrant/public"
  ServerName localhost
  <Directory "/vagrant/public">
    AllowOverride All
  </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-enabled/000-default

# Enable mod_rewrite
sudo a2enmod rewrite

# Restart Apache
sudo service apache2 restart

# Remove the Apache default /var/www directory and symlink instead to /vendor
rm -fr /var/www
ln -s /vagrant /var/www

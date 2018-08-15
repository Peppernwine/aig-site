sudo apt update && sudo apt upgrade
sudo apt install tasksel
sudo tasksel install lamp-server

sudo add-apt-repository ppa:ondrej/php
sudo apt-get update

sudo apt-get install php5.6
sudo apt-get install php5.6-mbstring php5.6-mcrypt php5.6-mysql php5.6-xml

sudo a2enmod rewrite
sudo systemctl restart apache2

sudo a2dismod php5.6
sudo a2enmod php7.0
sudo service apache2 restart

sudo apt-get install php5.6-curl
sudo php composer.phar update

sudo apt-get install zip unzip php5.6-zip

#enable mode rewrite..
sudo a2enmod rewrite
sudo systemctl restart apache2


# update /etc/apache2/sites-available/000-default.conf
#<VirtualHost *:80>
#    <Directory /var/www/html>
#        Options Indexes FollowSymLinks MultiViews
#        AllowOverride All
#        Require all granted
#    </Directory>

# update /etc/apache2/apache2.conf
#</VirtualHost>

#<Directory /var/www/>
#    Options Indexes FollowSymLinks
#    AllowOverride All
#    Require all granted
#</Directory>
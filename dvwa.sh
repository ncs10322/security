#!/bin/bash
yum install -y httpd mariadb-server mariadb
yum install -y httpd php php-mysqlnd php-gd
yum install -y php-mysql
systemctl enable --now mariadb httpd
echo -e "\n\ntoor1234.\ntoor1234.\ny\nn\ny\ny\n" | /usr/bin/mysql_secure_installation
mysql -uroot -ptoor1234. -e "create database dvwa; GRANT ALL PRIVILEGES ON dvwa.* TO 'dvwa'@'localhost' IDENTIFIED BY 'toor1234.'; flush privileges;"
wget https://github.com/ncs10322/aws/raw/main/DVWA.zip
unzip DVWA.zip
mv DVWA/* /var/www/html/
sed -i "s/p@ssw0rd/toor1234./g" /var/www/html/config/config.inc.php
sed -i 's/allow_url_include = Off/allow_url_include = on/g' /etc/php.ini
chmod 777 /var/www/html/hackable/uploads
chmod 777 /var/www/html/config
systemctl restart httpd.service

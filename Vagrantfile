Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.box_version = "20190429.0.1"
  config.vm.provision "shell", inline: <<-SHELL
	apt-get update
	apt-get dist-upgrade
	apt-add-repository -y ppa:ondrej/php
	apt-get update
	apt-get -y install unzip apache2 php7.3 php7.3-gd libapache2-mod-php7.3 php7.3-mysql php7.3-mcrypt php7.3-curl php7.3-xml php7.3-mbstring php7.3-zip npm nodejs git
        ln -s /usr/bin/nodejs /usr/bin/node
        npm cache clean -f
        npm install -g n
        n stable
        npm config set registry http://registry.npmjs.org/
        wget -k https://files.phpmyadmin.net/phpMyAdmin/4.0.10.11/phpMyAdmin-4.0.10.11-english.tar.gz
        sudo tar -xzvf phpMyAdmin-4.0.10.11-english.tar.gz -C /usr/share
        rm phpMyAdmin-4.0.10.11-english.tar.gz
        sudo mv /usr/share/phpMyAdmin-4.0.10.11-english/ /usr/share/phpmyadmin/
        sudo ln -s /usr/share/phpmyadmin /var/www/
	mkdir /var/www/concrete/
	sudo chown vagrant:vagrant /var/www/concrete/
	a2enmod rewrite
	rm -f /etc/apache2/sites-enabled/000-default.conf
	cat > /etc/apache2/sites-enabled/000-default.conf <<EOL
<VirtualHost *:80>
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/concrete

	<Directory /var/www/concrete>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
	</Directory>

        Alias /phpmyadmin "/var/www/phpmyadmin/"
        <Directory "/var/www/phpmyadmin/">
            Options Indexes MultiViews FollowSymLinks
            AllowOverride None
            Order allow,deny
            allow from all
            Allow from 127.3.0.0/255.0.0.0 ::1/128
        </Directory>

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOL
	sed -i 's/www-data/vagrant/g' /etc/apache2/envvars
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin
	debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
	debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
	apt-get -q -y install mysql-server mysql-client
	mysql -u root -proot -e "CREATE DATABASE concrete5"
	git clone https://github.com/concrete5/concrete5.git /var/www/concrete/
	cd /var/www/concrete/concrete
	composer install
	ln -fs /vagrant/packages/concrete_cms_theme /var/www/concrete/packages/concrete_cms_theme
	sudo /etc/init.d/apache2 restart
	chmod +x /var/www/concrete/concrete/bin/concrete5
    chmod -R 777 /var/www/concrete/application/files /var/www/concrete/application/config /var/www/concrete/packages
	/var/www/concrete/concrete/bin/concrete5 c5:install --db-server=localhost --db-username=root --db-password=root --db-database=concrete5 --admin-email=admin@example.com --admin-password=admin --starting-point=elemental_blank
	sudo chmod -R 777 /tmp/
    /var/www/concrete/concrete/bin/concrete5 c5:package-install concrete_cms_theme --full-content-swap
    cd /vagrant/build
    npm i
    npm run prod
    cat > /var/www/concrete/.htaccess <<EOL
<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase /
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME}/index.html !-f
	RewriteCond %{REQUEST_FILENAME}/index.php !-f
	RewriteRule . index.php [L]
</IfModule>
EOL
	sudo chown -R vagrant:vagrant /var/www/concrete/
SHELL

  config.vm.provision "shell", run: 'always', inline: <<-SHELL
        cd /vagrant/
        /vagrant/node_modules/grunt-cli/bin/grunt watch
SHELL

  config.vm.network :forwarded_port, guest: 80, host: 8080, auto_correct: true
  config.vm.network "private_network", type: "dhcp"
end

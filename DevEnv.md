### Big Picture ###
Zayso is a LAMP project currently using PHP, Apache and MySql.
Might move to Postgress in the future since mysql still has issues with views and stored procedures.

### Some Names ###
  * Zayso - Public name of the project
  * Osso - Open Source Sports Organizer, private name of the project
  * Cerad - My family names, use for a shared library

### Getting Started ###
  * Request to join the zayso2010 project on code.google.com

  * Install svn command line client, for Windows, use [SVN Silk](http://www.sliksvn.com/en/download)
  * Make a directory called /home/ahundiak/zayso2010 then check out the latest code
  * Fetch the wiki directory into /home/ahundiak/zayso2010/wiki as well

Note that I like to use /home/ahundiak for working in even on Windows systems.
I avoid hard coding this path but sometimes it is just plain easier.

  * Install and configure PHP, Apache and MySql.  For windows use the latest [xamp package](http://www.apachefriends.org/en/xampp-windows.html)
  * Create a virtual host pointing to /home/ahundiak/zayso2010/osso2010/web and test

  * Install and configure your favorite IDE.
I use [NetBeans](http://netbeans.org/downloads/) since it installs cleanly under both windows and Solaris 10.
I also had trouble configuring Eclipse with svn and proxy servers.
  * Adjust the Editor to insert two spaces for each tab.  Do not mess up my source code formating!
  * Adjust the svn settings and verify you can commit changes from within your IDE.

  * Install [PHP Unit Testing 3.4.x](http://www.phpunit.de/manual/current/en/installation.html).
I just ftp-ed the package instead of messing around with the pear installer.  No real need to configure it.

### Solaris 10 ###
php configure.sh

#!/bin/ksh
sudo ./configure \
> --prefix=/usr/local/php \
> --with-config-file-path=/usr/local/php/lib \
> --with-libxml-dir=/usr/local \
> --with-zlib=/usr/local \
> --with-xpm-dir=/usr/local \
> --with-mysql=/usr/local/mysql \
> --with-mysqli=/usr/local/mysql/bin/mysql\_config \
> --with-apxs2=/usr/local/apache2/bin/apxs \
> --without-pgsql \
> --with-jpeg-dir=/usr/local/lib \
> --with-zlib-dir=/usr/local/lib \
> --with-gd=/usr/local \
> --enable-mbstring \
> --enable-exif \
> --enable-force-cgi-redirect \
> --enable-sockets \
> --with-png-dir=/usr/local/lib \
> --with-curl=/usr/local \
> --with-ldap=/usr/local \
> --with-openssl=/usr/local/ssl \
> --with-gettext \
> --with-pcre-dir=/usr/local \
> --with-freetype-dir=/usr/local \
> --with-mssql=/usr/local/freetds \
> --with-pdo-mysql=/usr/local/mysql

apache.sh
sudo /usr/local/apache2/bin/apachectl stop
sudo /usr/local/apache2/bin/apachectl -f /usr/local/apache2/conf/httpd.conf

mysql.sh
#!/bin/ksh
sudo /usr/local/mysql/bin/mysqld\_safe --user=mysql &
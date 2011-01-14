# mysql -u root

SET PASSWORD FOR 'root'@'localhost' = PASSWORD('root894');

CREATE DATABASE osso2007;
GRANT ALL ON osso2007.* TO "impd"@"localhost";

SET PASSWORD FOR 'impd'@'localhost' = PASSWORD('impd894');

CREATE DATABASE session;
GRANT ALL ON    session.* TO "impd"@"localhost";

CREATE DATABASE eayso;
GRANT ALL ON    eayso.* TO "impd"@"localhost";

CREATE DATABASE osso;
GRANT ALL ON    osso.* TO "impd"@"localhost";

FLUSH PRIVILEGES;

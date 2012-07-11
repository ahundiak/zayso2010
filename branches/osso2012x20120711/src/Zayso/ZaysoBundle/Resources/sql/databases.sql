DROP DATABASE IF EXISTS eayso;
CREATE DATABASE         eayso;
GRANT ALL ON eayso.* TO "impd"@"localhost";

FLUSH PRIVILEGES;

== View database
DROP DATABASE IF EXISTS ossov;

CREATE DATABASE ossov;

GRANT ALL ON ossov.* TO "impd"@"localhost";

== New development database
DROP DATABASE IF EXISTS osso2012;

CREATE DATABASE osso2012;

GRANT ALL ON osso2012.* TO "impd"@"localhost";

DROP DATABASE IF EXISTS osso2012v;

CREATE DATABASE osso2012v;

GRANT ALL ON osso2012v.* TO "impd"@"localhost";

== Arbiter database
DROP DATABASE IF EXISTS arbiter;

CREATE DATABASE arbiter;

GRANT ALL ON arbiter.* TO "impd"@"localhost";

== S5Games database
DROP DATABASE IF EXISTS s5games;

CREATE DATABASE s5games;

GRANT ALL ON s5games.* TO "impd"@"localhost";

DROP DATABASE IF EXISTS s5gamesv;
CREATE DATABASE         s5gamesv;
GRANT ALL ON            s5gamesv.* TO "impd"@"localhost";
FLUSH PRIVILEGES;

== NatGames database
DROP DATABASE IF EXISTS natgames;
CREATE DATABASE         natgames;
GRANT ALL ON            natgames.* TO "impd"@"localhost";

DROP DATABASE IF EXISTS natgamesv;
CREATE DATABASE         natgamesv;
GRANT ALL ON            natgamesv.* TO "impd"@"localhost";
FLUSH PRIVILEGES;

== Done
FLUSH PRIVILEGES;

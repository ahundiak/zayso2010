<?php

$uri  = $_SERVER['REQUEST_URI']; //  /test7/ 	/test7/a/account */

$name = $_SERVER['SCRIPT_NAME']; //  /test7/index.php

if (substr($uri,0,strlen($name) == $name)) $args = substr($uri,strlen($name)+1);
else                                       $args = substr($uri,strlen(dirname($name)) + 1);

echo "UIR: $uri <br />NAME: $name<br />";
print_r($_GET);

?>

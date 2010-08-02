<?php
// echo "IN app/sub/index.php\n<br />";

$uri  = $_SERVER['REQUEST_URI'];

$name = $_SERVER['SCRIPT_NAME'];

if (substr($uri,0,strlen($name)) == $name) $args = substr($uri,strlen($name)+1);
else                                       $args = substr($uri,strlen(dirname($name)) + 1);

// echo "URI: $uri <br />NAME: $name<br />ARGS: $args</br />";
if (!$args) $args = 'default';

$slash = strpos($args,'/');
if ($slash === FALSE) $cont = $args;
else                  $cont = substr($args,0,$slash);

require $cont . '.php';

/*
 * http://local.osso2010.org/app/sub/           NAME: /app/sub/index.php URI: /app/sub/
 * http://local.osso2010.org/app/sub/index.php  NAME: /app/sub/index.php URI: /app/sub/index.php
 *
 * This is where the browser messes up
 * http://local.osso2010.org/app/sub/default/id/1
 *
 * href=redirect generates
 * http://local.osso2010.org/app/sub/default/id/redirect
 *
 * It's not a redirect thing but rather how the browser works
 * Could probably get around it by redirecting for GET requests
 */
?>

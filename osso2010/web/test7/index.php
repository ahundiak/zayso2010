<?php
// echo phpinfo();
$uri  = $_SERVER['REQUEST_URI']; //  /test7/ 	/test7/a/account */

$name = $_SERVER['SCRIPT_NAME']; //  /test7/index.php

echo "UIR: $uri <br />NAME: $name<br />";
die();
$path = dirname($name);          //  /test7

$args = substr($uri,strlen($path) + 1);  // a/account

$args = explode('/',$args);
$count = count($args);
$request = array();
for($i = 0; $i < $count; $i += 2)
{
  $key = $args[$i];
  if (($i + 1) < $count) $request[$key] = $args[$i+1];
  else                   $request[$key] = NULL;
}
print_r($request);

//if (isset($request['b'])) echo 'b is set';
//else                      echo 'b is not set';
?>

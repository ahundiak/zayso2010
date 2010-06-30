<?php
require_once '../config.php';

$argv = $_SERVER['argv'];
$cmd = "php email3.php message list\n";

if (isset($argv[1])) $msg = $argv[1];
else                 die($cmd);

if (isset($argv[2])) $list = $argv[2];
else                 die($cmd);

echo "$msg $list\n";
		
?>

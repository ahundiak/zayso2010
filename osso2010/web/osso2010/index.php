<?php

if (isset($_COOKIE['osso2010'])) $sessionId = $_COOKIE['osso2010'];
else                             $sessionId = 'NO SESSION ID';

$log = $_SERVER['REQUEST_URI'] . ' ' . $sessionId . "\n";
file_put_contents('log.txt',$log,FILE_APPEND);

$config = include '../config.php';

$config['web_app_path'] = '/osso2010/';

include $config['ws'] . 'osso2010/apps/osso/FrontEnd/FrontCont.php';

?>

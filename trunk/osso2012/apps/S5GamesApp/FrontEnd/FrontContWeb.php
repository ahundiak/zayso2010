<?php

namespace S5GamesApp\FrontEnd;

use
  Cerad\Debug,
  Cerad\ClassLoader;

require 'FrontCont.php';

// Merge in additional config items
// $configx = require $config['ws'] . 'osso2010/model/config/config_' . $config['web_host'] . '.php';

$configx = require $config['ws'] . 'osso2012/model/config/config.php';
$config  = array_merge($config,$configx);

//$configx = require $config['ws'] . 'osso2007/osso2007/apps/osso/config/config.php';
//$config  = array_merge($config,$configx);

$configx = NULL;

$fc = new FrontCont($config);
$config = NULL;
$fc->process();

?>

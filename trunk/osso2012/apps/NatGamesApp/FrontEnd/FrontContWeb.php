<?php

namespace NatGamesApp\FrontEnd;

use
  Cerad\Debug,
  Cerad\ClassLoader;

require 'FrontCont.php';

// Merge in additional config items
$configx = require $config['ws'] . 'osso2012/model/config/config.php';

$config  = array_merge($config,$configx);

unset($configx);

$fc = new FrontCont($config);
unset($config);
$fc->process();

?>

<?php
/* ==========================================================
 * Use this to kick things off from the web
 * Allows the real controller to be called from a script for testing
 * Not really sure that is needed anymore but doesn't hurt
 */
namespace Area5CFApp\base;

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

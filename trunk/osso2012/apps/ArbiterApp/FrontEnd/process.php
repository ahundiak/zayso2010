<?php
namespace ArbiterApp\FrontEnd;

error_reporting(E_ALL);

use
  Cerad\Debug,
  Cerad\ClassLoader;

$config = array
(
    'ws' => '/home/ahundiak/zayso2012/',
);

require 'FrontCont.php';

class MyProcess extends FrontCont
{

  function process()
  {
    $stats = new \ArbiterApp\RefStats\RefStatsProcess($this->services);
    $stats->import('C:/home/ahundiak/datax/arbiter/AHSAA/Week03/ScheduleWeek03.csv');
  }
}

$configx = require $config['ws'] . 'osso2012/model/config/config.php';
$config  = array_merge($config,$configx);

$configx = NULL;

$fc = new MyProcess($config);
$config = NULL;
$fc->process();
?>

<?php

namespace ZaysoApp\FrontEnd;

use
  Cerad\Debug,
  Cerad\ClassLoader;

require $config['ws'] . 'CeradNS/library/Cerad/FrontEnd/FrontCont.php';

class FrontCont extends \Cerad\FrontEnd\FrontCont
{
  protected $actions = array
  (
    'welcome' => 'ZaysoApp\Welcome\WelcomeAction',
    'team-schedule-show' => 'ZaysoApp\Team\Schedule\Show\SchTeamShowAction',
  );
  protected function init()
  {
    parent::init();

    $ws = $this->config['ws'];

    ClassLoader::createNS('AYSO',     $ws . 'osso2012/model/Entities');
    ClassLoader::createNS('OSSO',     $ws . 'osso2012/model/Entities');

    ClassLoader::createNS('ZaysoApp', $ws . 'osso2012/apps');

    // For templates
    ini_set('include_path',PATH_SEPARATOR . $ws . 'osso2012/apps');

    // Make sure we get correct Service object
    $this->services = new Services($this->config);

    return;
  }
}
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

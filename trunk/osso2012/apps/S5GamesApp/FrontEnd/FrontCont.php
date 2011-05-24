<?php

namespace S5GamesApp\FrontEnd;

use
  Cerad\Debug,
  Cerad\ClassLoader;

require $config['ws'] . 'CeradNS/library/Cerad/FrontEnd/FrontCont.php';

class FrontCont extends \Cerad\FrontEnd\FrontCont
{
  protected $actions = array
  (
    'welcome'   => 'S5GamesApp\Welcome\WelcomeAction',
    'signin'    => 'S5GamesApp\Account\Signin\SigninAction',

    'ref_avail' => 'S5GamesApp\RefAvail\RefAvailAction',
    'schedule'  => 'S5GamesApp\Schedule\ScheduleAction',
  );
  protected function init()
  {
    parent::init();

    $ws = $this->config['ws'];

    ClassLoader::createNS('AYSO',       $ws . 'osso2012/model/Entities');
    ClassLoader::createNS('S5Games',    $ws . 'osso2012/model/Entities');
    ClassLoader::createNS('Session',    $ws . 'osso2012/model/Entities');

    ClassLoader::createNS('S5GamesApp', $ws . 'osso2012/apps');

    // For templates
    ini_set('include_path',PATH_SEPARATOR . $ws . 'osso2012/apps');

    // Make sure we get correct Service object
    $this->services = new Services($this->config);

    return;
  }
}
?>

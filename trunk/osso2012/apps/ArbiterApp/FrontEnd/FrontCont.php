<?php

namespace ArbiterApp\FrontEnd;

use
  Cerad\Debug,
  Cerad\ClassLoader;

require $config['ws'] . 'CeradNS/library/Cerad/FrontEnd/FrontCont.php';

class FrontCont extends \Cerad\FrontEnd\FrontCont
{
  protected $actions = array
  (
    'welcome'   => 'ArbiterApp\Welcome\WelcomeAction',
    'ref_avail' => 'ArbiterApp\RefAvail\RefAvailAction',
    'schedule'  => 'ArbiterApp\Schedule\ScheduleAction',
  );
  protected function init()
  {
    parent::init();

    $ws = $this->config['ws'];

    ClassLoader::createNS('AYSO',       $ws . 'osso2012/model/Entities');
    ClassLoader::createNS('Arbiter',    $ws . 'osso2012/model/Entities');

    ClassLoader::createNS('ArbiterApp', $ws . 'osso2012/apps');

    // For templates
    ini_set('include_path',PATH_SEPARATOR . $ws . 'osso2012/apps');

    // Make sure we get correct Service object
    $this->services = new Services($this->config);

    return;
  }
}
?>

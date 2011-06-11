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
    'home'      => 'S5GamesApp\Home\HomeAction',
    
    'account-signin'  => 'S5GamesApp\Account\Signin\SigninAction',
    'account-signout' => 'S5GamesApp\Account\Signout\SignoutAction',
    'account-create'  => 'S5GamesApp\Account\Create\CreateAction',
    'account-update'  => 'S5GamesApp\Account\Update\UpdateAction',
    'account-list'    => 'S5GamesApp\Account\Listx\ListAction',

    'admin-clear'     => 'S5GamesApp\Admin\ClearAction',
    'session-show'    => 'S5GamesApp\Admin\Session\ShowAction',

    'schedule-show'   => 'S5GamesApp\Schedule\Show\ShowAction',
    'schedule-assign' => 'S5GamesApp\Schedule\Assign\AssignAction',
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
    ini_set('include_path',$ws . 'osso2012/apps');

    // Make sure we get correct Service object
    $this->services = new Services($this->config);

    return;
  }
}
?>

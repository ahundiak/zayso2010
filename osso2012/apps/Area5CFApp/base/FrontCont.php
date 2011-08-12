<?php

namespace Area5CFApp\base;

use \Cerad\ClassLoader;

require $config['ws'] . 'osso2012/libs/Cerad/FrontCont.php';

class FrontCont extends \Cerad\FrontCont
{
  protected $actions = array
  (
    'welcome'   => 'Area5CFApp\Welcome\WelcomeAction',
    
    'home'      => 'Area5CFApp\Home\HomeAction',
    'contact'   => 'Area5CFApp\Home\ContactAction',
    
    'account-signin'  => 'Area5CFApp\Account\Signin\SigninAction',
    'account-signout' => 'Area5CFApp\Account\Signout\SignoutAction',
    'account-create'  => 'Area5CFApp\Account\Create\CreateAction',
    'account-update'  => 'Area5CFApp\Account\Update\UpdateAction',
    'account-list'    => 'Area5CFApp\Account\Listx\ListAction',

    'admin-clear'     => 'Area5CFApp\Admin\ClearAction',
    'admin-import'    => 'Area5CFApp\Admin\Import\ImportAction',
    'session-show'    => 'Area5CFApp\Admin\Session\ShowAction',

    'projinfo-reflevel'  => 'NatGamesApp\ProjInfo\RefLevel\RefLevelAction',
    'projinfo-plans'     => 'NatGamesApp\ProjInfo\Plans\PlansAction',

    'schedule-show'   => 'S5GamesApp\Schedule\Show\ShowAction',
    'schedule-stats'  => 'S5GamesApp\Schedule\Stats\StatsAction',
    'schedule-assign' => 'S5GamesApp\Schedule\Assign\AssignAction',
  );
  protected function init()
  {
    parent::init();

    $ws = $this->config['ws'];

    ClassLoader::createNS('Session',  $ws . 'osso2012/model/Entities');
    ClassLoader::createNS('OSSO2012', $ws . 'osso2012/model/Entities');
    ClassLoader::createNS('Area5CF',  $ws . 'osso2012/model/Entities');

    ClassLoader::createNS('Area5CFApp', $ws . 'osso2012/apps');

    // For templates
    ini_set('include_path',$ws . 'osso2012/apps');

    // Make sure we get correct Service object
    $this->services = new Services($this->config);

    return;
  }
}
?>

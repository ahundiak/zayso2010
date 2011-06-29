<?php

namespace NatGamesApp\FrontEnd;

use
  Cerad\Debug,
  Cerad\ClassLoader;

require $config['ws'] . 'CeradNS/library/Cerad/FrontEnd/FrontCont.php';

class FrontCont extends \Cerad\FrontEnd\FrontCont
{
  protected $actions = array
  (
    'welcome'   => 'NatGamesApp\Home\WelcomeAction',
    'home'      => 'NatGamesApp\Home\HomeAction',
    'contact'   => 'NatGamesApp\Home\ContactAction',
    
    'account-signin'  => 'NatGamesApp\Account\Signin\SigninAction',
    'account-signout' => 'NatGamesApp\Account\Signout\SignoutAction',
    'account-create'  => 'NatGamesApp\Account\Create\CreateAction',
    'account-update'  => 'NatGamesApp\Account\Update\UpdateAction',
    'account-list'    => 'NatGamesApp\Account\Listx\ListAction',

    'admin-clear'     => 'NatGamesApp\Admin\ClearAction',
    'session-show'    => 'NatGamesApp\Admin\Session\ShowAction',

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
    ClassLoader::createNS('NatGames', $ws . 'osso2012/model/Entities');

    ClassLoader::createNS('NatGamesApp', $ws . 'osso2012/apps');

    // For templates
    ini_set('include_path',$ws . 'osso2012/apps');

    // Make sure we get correct Service object
    $this->services = new Services($this->config);

    return;
  }
}
?>

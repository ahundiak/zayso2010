<?php

namespace Area5CF\base;

use
  Cerad\Debug,
  Doctrine\ORM\EntityManager,
  Doctrine\Common\EventManager,
  Doctrine\ORM\Configuration;

class Services extends \Cerad\Services
{
  protected $repoMap = array
  (
    'gameRepo'    => array('em' => 'gameEm',    'item' => 'NatGames\Game\GameItem'),
    'personRepo'  => array('em' => 'workEm',    'item' => 'NatGames\Person\PersonItem'),
    'projectRepo' => array('em' => 'workEm',    'item' => 'NatGames\Project\ProjectItem'),
    'accountRepo' => array('em' => 'workEm',    'item' => 'NatGames\Account\AccountItem'),
    'sessionRepo' => array('em' => 'sessionEm', 'item' => 'Session\SessionDataItem'),
  );
  protected $itemMap = array
  (
    'gameItem'    => array('em' => 'gameEm',    'item' => 'NatGames\Game\GameItem'),
    'personItem'  => array('em' => 'workEm',    'item' => 'NatGames\Person\PersonItem'),
    'projectItem' => array('em' => 'workEm',    'item' => 'NatGames\Project\ProjectItem'),
    'accountItem' => array('em' => 'workEm',    'item' => 'NatGames\Account\AccountItem'),
  );
}
?>

<?php
/* --------------------------------------------------------------------
 * Even though this is a base, move some of the functionality here for now
 * Later on want to refactor into a more specific manager
 * But have to many copies of stuff for now
 */
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

class ScheduleManager extends BaseManager
{
    public function loadGames($params)
    {
        $projectId = $params['projectId'];
        
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('game');
        $qb->addSelect('field');
        $qb->addSelect('gameTeam');
        $qb->addSelect('schTeam');
        $qb->addSelect('phyTeam');
        $qb->addSelect('gamePerson');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:Event','game');
        $qb->leftJoin('game.field',       'field');
        $qb->leftJoin('game.teams',       'gameTeam');
        $qb->leftJoin('gameTeam.team',    'schTeam');
        $qb->leftJoin('schTeam.parent',   'phyTeam');
        $qb->leftJoin('game.persons',     'gamePerson');
        $qb->leftJoin('gamePerson.person','person');

        $qb->andWhere($qb->expr()->eq('game.project',$qb->expr()->literal($projectId)));
        
        return $qb->getQuery()->getResult();
        
    }
  
 }
?>

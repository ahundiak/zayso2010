<?php
/* --------------------------------------------------------------------
 * Even though this is a base, move some of the functionality here for now
 * Later on want to refactor into a more specific manager
 * But have to many copies of stuff for now
 */
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Doctrine\ORM\ORMException;

class GameManager extends BaseManager
{
  
    /* ========================================================================
     * Single event stuff
     * Verified used by game edit and referee assinging
     * Game Report
     */
    public function loadEventForId($id)
    {
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('event');
        $qb->addSelect('field');
        $qb->addSelect('eventTeam');
        $qb->addSelect('team');
        $qb->addSelect('eventPerson');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:Event', 'event');
        $qb->leftJoin('event.project',     'project');
        $qb->leftJoin('event.field',       'field');
        $qb->leftJoin('event.teams',       'eventTeam');
        $qb->leftJoin('eventTeam.team',    'team');
        $qb->leftJoin('event.persons',     'eventPerson');
        $qb->leftJoin('eventPerson.person','person');

        $qb->andWhere($qb->expr()->eq('event.id',$qb->expr()->literal($id)));
        
        return $qb->getQuery()->getOneOrNullResult();

    }
    public function loadPhyTeamForKey($projectId,$key)
    {
        if (!$key) return null;
        
        $qb = $this->newQueryBuilder();
        $qb->addSelect('team');

        $qb->from('ZaysoCoreBundle:Team', 'team');

        $qb->andWhere($qb->expr()->eq('team.project',$qb->expr()->literal($projectId)));
        $qb->andWhere($qb->expr()->eq('team.key1',   $qb->expr()->literal($key)));
        $qb->andWhere($qb->expr()->eq('team.type',   $qb->expr()->literal('physical')));
        
        return $qb->getQuery()->getOneOrNullResult();
        
    }
    public function loadSchTeamForKey($projectId,$key)
    {
        if (!$key) return null;
        
        $qb = $this->newQueryBuilder();
        
        $qb->addSelect('team','phyTeam');

        $qb->from('ZaysoCoreBundle:Team','team');
        
        $qb->leftJoin('team.parent','phyTeam');

        $qb->andWhere($qb->expr()->eq('team.project',$qb->expr()->literal($projectId)));
        $qb->andWhere($qb->expr()->eq('team.key1',   $qb->expr()->literal($key)));
        $qb->andWhere($qb->expr()->eq('team.type',   $qb->expr()->literal('schedule')));
        
        return $qb->getQuery()->getOneOrNullResult(); 
    }
    public function loadFieldForKey($projectId,$key)
    {
        if (!$key) return null;
        
        $qb = $this->newQueryBuilder();
        
        $qb->addSelect('field');

        $qb->from('ZaysoCoreBundle:ProjectField','field');
        
        $qb->andWhere($qb->expr()->eq('field.project',$qb->expr()->literal($projectId)));
        $qb->andWhere($qb->expr()->eq('field.key1',   $qb->expr()->literal($key)));
        
        return $qb->getQuery()->getOneOrNullResult(); 
    }
    public function loadEventForNum($projectId,$num)
    {
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('event');
        $qb->addSelect('field');
        $qb->addSelect('eventTeam');
        $qb->addSelect('team');
        $qb->addSelect('phyTeam');
        
        $qb->from('ZaysoCoreBundle:Event', 'event');
        $qb->leftJoin('event.project',     'project');
        $qb->leftJoin('event.field',       'field');
        $qb->leftJoin('event.teams',       'eventTeam');
        $qb->leftJoin('eventTeam.team',    'team');
        $qb->leftJoin('team.parent',       'phyTeam');

        $qb->andWhere($qb->expr()->eq('event.project',$qb->expr()->literal($projectId)));
        $qb->andWhere($qb->expr()->eq('event.num',    $qb->expr()->literal($num)));
        
        return $qb->getQuery()->getOneOrNullResult();

    }
}
?>

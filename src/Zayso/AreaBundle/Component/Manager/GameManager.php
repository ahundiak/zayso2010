<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\AreaBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event;
use Zayso\CoreBundle\Entity\EventTeam;
use Zayso\CoreBundle\Entity\ProjectField;

class GameManager extends BaseManager
{
    /* ========================================================================
     * Not used, keep for now, merge with load by id
     */
    public function loadEventForProjectNum($projectId,$num)
    {
        // Just because
        if (is_object($projectId)) $projectId = $projectId->getId();

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('event');
        $qb->addSelect('eventTeam');
        $qb->addSelect('team');
        $qb->addSelect('field');

        $qb->from('ZaysoCoreBundle:Event','event');
        $qb->leftJoin('event.teams',      'eventTeam');
        $qb->leftJoin('eventTeam.team',   'team');
        $qb->leftJoin('event.project',    'project');
        $qb->leftJoin('event.field',      'field');

        $qb->andWhere($qb->expr()->eq('project.id',$qb->expr()->literal($projectId)));

        $qb->andWhere($qb->expr()->eq('event.num',$qb->expr()->literal($num)));

        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];

        return null;
    }

    /* ===================================================================
     * Get next available game number fo a given project
     * Verified being used
     */
    public function loadTeamForKey($projectId,$key)
    {
        // Just because
        if (is_object($projectId)) $projectId = $projectId->getId();
        
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('team');

        $qb->from('ZaysoCoreBundle:Team','team');
        $qb->leftJoin('team.project','project');

        $qb->andWhere($qb->expr()->eq('project.id',$qb->expr()->literal($projectId)));
        
        $qb->andWhere($qb->expr()->eq('team.key1',$qb->expr()->literal($key)));
        
        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];
        
        return null;
    }
    /* ------------------------------------------------------------------------------
     * Access Field
     */
    public function newProjectField($project = null) 
    { 
        if (!is_object($project)) $project = $this->getProjectReference($project);
        $field = new ProjectField();
        $field->setProject($project);
        return $field;
    }
    public function loadProjectFieldForKey($projectId,$key)
    {
        // Just because
        if (is_object($projectId)) $projectId = $projectId->getId();
        
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('projectField');

        $qb->from('ZaysoCoreBundle:ProjectField','projectField');
        $qb->leftJoin('projectField.project','project');

        $qb->andWhere($qb->expr()->eq('project.id',$qb->expr()->literal($projectId)));
        
        $qb->andWhere($qb->expr()->eq('projectField.key1',$qb->expr()->literal($key)));
        
        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];
        
        return null;
    }
    

}
?>

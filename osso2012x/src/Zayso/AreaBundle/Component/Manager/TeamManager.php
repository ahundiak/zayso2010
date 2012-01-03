<?php
namespace Zayso\AreaBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Project;

use Zayso\CoreBundle\Entity\Team;

class TeamManager extends BaseManager
{
    protected $em = null;
    
    public function newTeam() { return new Team(); }

    /* ==========================================================================
     * Dtandard generic team
     */
    public function loadTeamForKey($projectId,$key,$keyName)
    {
        if (is_object($projectId)) $projectId = $projectId->getId();
        
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('team');

        $qb->from('ZaysoCoreBundle:Team','team');
        $qb->leftJoin('team.project','project');

        $qb->andWhere($qb->expr()->eq('project.id',$qb->expr()->literal($projectId)));
        
        $qb->andWhere($qb->expr()->eq('team.' . $keyName,$qb->expr()->literal($key)));
        
        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];
        
        return null;
    }
    public function loadTeamForTeamKey($projectId,$key)
    {
        return $this->loadTeamForKey($projectId,$key,'key1');
    }
    public function loadTeamForEaysoId($projectId,$key)
    {
        return $this->loadTeamForKey($projectId,$key,'key3');
    }
    
}
?>

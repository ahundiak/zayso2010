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
    

    /* ==========================================================================
     * Query for a bunch of teams
     */
    public function queryTeams($params,$qbOnly = false)
    {
       // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('team');
        $qb->addSelect('project');
        $qb->addSelect('org');

        $qb->from('ZaysoCoreBundle:Team','team');
        $qb->leftJoin('team.project','project');
        $qb->leftJoin('team.org',    'org');
        
        if (isset($params['projectId']))
        {
            $qb->andWhere($qb->expr()->eq('team.project',$qb->expr()->literal($params['projectId'])));
        }
        $qb->addOrderBy('team.key1');
        
        if ($qbOnly) return $qb;
        
        return $qb->getQuery()->getResult();
        
   }
    /* ==========================================================================
     * Standard generic team
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

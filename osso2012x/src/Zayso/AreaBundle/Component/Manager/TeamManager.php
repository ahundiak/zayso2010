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
    
    /* =================================================================
     * Clone an existing validated team
     */
    public function cloneTeam($team)
    {
        $teamx = new Team();
        
        $teamx->setProject($team->getProject());
        $teamx->setParent ($team->getParent());
        $teamx->setOrg    ($team->getOrg());
        $teamx->setType   ($team->getType());
        $teamx->setSource ('manual');
        $teamx->setStatus ('status');
        $teamx->setAge    ($team->getAge());
        $teamx->setGender ($team->getGender());
        $teamx->setLevel  ($team->getLevel());
        
        $teamx->setDesc1  ($team->getDesc1());
        $teamx->setDesc2  ($team->getDesc2());
            
        $teamx->setTeamKey(md5(uniqid()));
        return $teamx;
    }

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
            $qb->andWhere($qb->expr()->in('project.id',$params['projectId']));
        }
        if (isset($params['teamId']))
        {
            $qb->andWhere($qb->expr()->in('team.id',$params['teamId']));
        }
        $qb->addOrderBy('team.key1');
        
        if ($qbOnly) return $qb;
        
        return $qb->getQuery()->getResult();
        
    }
    public function queryTeam($teamId)
    {
        $teams = $this->queryTeams(array('teamId' => $teamId));
        if (count($teams) == 1) return $teams[0];
        return null;
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

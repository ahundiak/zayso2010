<?php
namespace Zayso\ZaysoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\ProjectSeqn;
use Zayso\ZaysoBundle\Entity\ProjectPerson;

use Zayso\ZaysoBundle\Entity\Person;
use Zayso\ZaysoBundle\Entity\PersonRegistered;
use Zayso\ZaysoBundle\Entity\AccountPerson;

/* =========================================================================
 * The repository
 */
class GameRepository extends EntityRepository
{
    // Schedule query
    public function queryGames($refSchedData)
    {
        if (isset($refSchedData['ages']))
        {
            $ages = $refSchedData['ages'];
            if (isset($ages['All'])) $ages = null;
        }
        else $ages = null;

        if (isset($refSchedData['regions']))
        {
            $regions = $refSchedData['regions'];
            if (isset($regions['All'])) $regions = null;
        }
        else $regions = null;

        if (isset($refSchedData['genders']))
        {
            $genders = $refSchedData['genders'];
            if (isset($genders['All' ])) $genders = null;
            if (isset($genders['Boys'])) $genders['Coed'] = 'C';
        }
        else $genders = null;

        if (isset($refSchedData['sortBy'])) $sortBy = $refSchedData['sortBy'];
        else                                $sortBy = 1;

        if (isset($refSchedData['date1'])) $date1 = $refSchedData['date1'];
        else                               $date1 = null;
        if (isset($refSchedData['date2'])) $date2 = $refSchedData['date2'];
        else                               $date2 = null;

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('game');
        $qb->addSelect('gameTeam');
        $qb->addSelect('person');

        $qb->from('ZaysoBundle:Game','game');

        $qb->leftJoin('game.gameTeams','gameTeam');
        $qb->leftJoin('game.persons',  'person');

        if ($date1) $qb->andWhere($qb->expr()->gte('game.date',$date1));
        if ($date2) $qb->andWhere($qb->expr()->lte('game.date',$date2));

        if (count($ages))    $qb->andWhere($qb->expr()->in('gameTeam.age',   $ages));    // Only work if both teams the same
        if (count($regions)) $qb->andWhere($qb->expr()->in('gameTeam.orgKey',$regions)); // Only work if both teams the same
        if (count($genders)) $qb->andWhere($qb->expr()->in('gameTeam.gender',$genders)); // Only work if both teams the same

        switch($sortBy)
        {
            case 1:
                $qb->addOrderBy('game.date');
                $qb->addOrderBy('game.time');
                $qb->addOrderBy('game.fieldKey');
                break;
            case 2:
                $qb->addOrderBy('game.date');
                $qb->addOrderBy('game.fieldKey');
                $qb->addOrderBy('game.time');
                break;
            case 3:
                $qb->addOrderBy('game.date');
                $qb->addOrderBy('game.age');
                $qb->addOrderBy('game.time');
                break;
        }

        $query = $qb->getQuery();

        $items = $query->getResult();
        return $items;
    }
    /* ========================================================================
     * Basic one game
     */
    public function loadGame($project,$num)
    {
        $em = $this->getEntityManager();
    
        if (is_object($project)) $projectId = $project->getId();
        else                     $projectId = $project;
    
        $search = array('project' => $projectId, 'num' => $num);

        $item = $this->findOneBy($search);
        if ($item) return $item;

        return null;
    }
    /* ========================================================================
     * Schedule team
     */
    public function loadSchTeam($project,$key)
    {
        $em = $this->getEntityManager();

        // Search for existing
        $repo = $em->getRepository('ZaysoBundle:SchTeam');

        if (is_object($project)) $projectId = $project->getId();
        else                     $projectId = $project;

        $search = array('project' => $projectId, 'teamKey' => $key);

        $item = $repo->findOneBy($search);
        if ($item) return $item;

        $search = array('project' => $projectId, 'teamKey2' => $key);
        $item = $repo->findOneBy($search);
        if ($item) return $item;

        return null;
    }
}
?>

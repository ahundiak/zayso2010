<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\Osso2007Bundle\Service;

use Zayso\Osso2007Bundle\Component\Debug;

use Zayso\Osso2007Bundle\Entity\Event;
use Zayso\Osso2007Bundle\Entity\EventTeam;

use Zayso\Osso2007Bundle\Entity\SchTeam;
use Zayso\Osso2007Bundle\Entity\PhyTeam;

class TeamManager extends BaseManager
{
    protected $em = null;
    
    /* ==========================================================
     * Physical Teams query
     * Might want to join the coaches since always want them anyways
     */
    public function queryPhyTeams($search,$games = array())
    {
        // Pull params
        $searchx = $this->getSearchParams($search);

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('phyTeam');
        $qb->addSelect('persons');
        $qb->addSelect('person');
        $qb->addSelect('unit');
        $qb->addSelect('division');

        $qb->from('Osso2007Bundle:PhyTeam', 'phyTeam');
        $qb->leftJoin('phyTeam.projectItem','projectItem'); // Hack, need type=2
        $qb->leftJoin('phyTeam.persons',    'persons');
        $qb->leftJoin('persons.person',     'person');

        $qb->leftJoin('phyTeam.unit',       'unit');
        $qb->leftJoin('phyTeam.division',   'division');

        if (count($searchx['projectIds']))
        {
            $qb->andWhere($qb->expr()->in('projectItem.projectId', $searchx['projectIds']));
            $qb->andWhere($qb->expr()->in('projectItem.typeId', array(2)));
        }
        if (count($searchx['divisionIds'])) $qb->andWhere($qb->expr()->in('division.divisionId',$searchx['divisionIds']));
        if (count($searchx['regionIds']))   $qb->andWhere($qb->expr()->in('unit.unitId',        $searchx['regionIds']));

        $qb->addOrderBy('unit.keyx');
        $qb->addOrderBy('division.descPick');
        $qb->addOrderBy('phyTeam.divisionSeqNum');

      //die($qb->getQuery()->getSql());

        $teams = $qb->getQuery()->getResult();
        return $teams;

    }
    /* ==========================================================
     * Schedule Teams query
     */
    public function querySchTeams($search,$games = array())
    {
        // Pull params
        $searchx = $this->getSearchParams($search);

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('schTeam');

        $qb->from('Osso2007Bundle:SchTeam','schTeam');

        if (count($searchx['projectIds']))  $qb->andWhere($qb->expr()->in('schTeam.projectId', $searchx['projectIds']));

        if (count($searchx['divisionIds'])) $qb->andWhere($qb->expr()->in('schTeam.divisionId',$searchx['divisionIds']));
        if (count($searchx['regionIds']))   $qb->andWhere($qb->expr()->in('schTeam.unitId',    $searchx['regionIds']));

        $qb->addOrderBy('schTeam.descShort');

        $teams = $qb->getQuery()->getResult();
        return $teams;
    }
    public function getSchTeamPickList($search,$games = array())
    {
        $teams = $this->querySchTeams($search,$games);
        $options = array();
        foreach($teams as $team)
        {
            $key = $team->getDescShort();
            $desc = sprintf('%s-%s-%s',substr($key,0,5),substr($key,5,4),substr($key,9,2));

            $coach = $team->getHeadCoach();
            if ($coach) $desc .= ' ' . $coach->getLastName();

            $options[$team->getId()] = $desc;
        }
        return $options;
    }
}
?>

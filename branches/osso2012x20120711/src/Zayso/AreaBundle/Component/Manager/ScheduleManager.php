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

class ScheduleManager extends BaseManager
{
    protected function getValues($search,$name,$default = null)
    {
        if (!isset($search[$name])) return $default;

        $values = $search[$name];
        if (!is_array($values)) return $values;

        $valuesIndexed = array();
        foreach($values as $value)
        {
            if ($value) $valuesIndexed[$value] = $value;
        }
        return $valuesIndexed;
    }
    /* ==========================================================
     * Game Schedule query
     */
    public function queryGames($search)
    {
        // Pull params
        $ages    = $this->getValues($search,'ages');
        $genders = $this->getValues($search,'genders');
        $regions = $this->getValues($search,'regions');

        $sortBy  = $this->getValues($search,'sortBy',1);
        $date1   = $this->getValues($search,'date1');
        $date2   = $this->getValues($search,'date2');
        $time1   = $this->getValues($search,'time1');
        $time2   = $this->getValues($search,'time2');

        $projectId = $this->getValues($search,'projectId');

        // Build query
        $em = $this->getEntityManager();
        $qbGameId = $em->createQueryBuilder();

        $qbGameId->addSelect('distinct gameGameId.id');

        $qbGameId->from('ZaysoCoreBundle:Event','gameGameId');

        $qbGameId->leftJoin('gameGameId.teams',   'gameTeamGameId');
        $qbGameId->leftJoin('gameTeamGameId.team','teamGameId');

        if ($projectId) $qbGameId->andWhere($qbGameId->expr()->in('gameGameId.projectId',$projectId));

        if ($date1) $qbGameId->andWhere($qbGameId->expr()->gte('gameGameId.date',$date1));
        if ($date2) $qbGameId->andWhere($qbGameId->expr()->lte('gameGameId.date',$date2));

        if ($time1) $qbGameId->andWhere($qbGameId->expr()->gte('gameGameId.time',$time1));
        if ($time2) $qbGameId->andWhere($qbGameId->expr()->lte('gameGameId.time',$time2));

        if ($ages)    $qbGameId->andWhere($qbGameId->expr()->in('teamGameId.age',   $ages));
        if ($genders) $qbGameId->andWhere($qbGameId->expr()->in('teamGameId.gender',$genders));
        
        if ($regions) 
        {
            // $regions[] = NULL;
            // $qbGameId->andWhere($qbGameId->expr()->in('teamGameId.org',   $regions));
            
            $qbGameId->andWhere($qbGameId->expr()->orX(
                $qbGameId->expr()->in('teamGameId.org',$regions),
                $qbGameId->expr()->isNull('teamGameId.org')
            ));

        }
        //$gameIds = $qbGameId->getQuery()->getArrayResult();
        //Debug::dump($gameIds);die();
        //return $gameIds;

        // Games
        $qbGames = $em->createQueryBuilder();

        $qbGames->addSelect('game');
        $qbGames->addSelect('gameTeam');
        $qbGames->addSelect('team');
        $qbGames->addSelect('field');

        $qbGames->addSelect('gamePerson');
        $qbGames->addSelect('person');

        $qbGames->from('ZaysoCoreBundle:Event','game');

        $qbGames->leftJoin('game.teams',   'gameTeam');
        $qbGames->leftJoin('game.persons', 'gamePerson');
        $qbGames->leftJoin('game.field',   'field');

        $qbGames->leftJoin('gameTeam.team',     'team');
        $qbGames->leftJoin('gamePerson.person', 'person');

        $qbGames->andWhere($qbGames->expr()->in('game.id',$qbGameId->getDQL()));

        switch($sortBy)
        {
            case 1:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('game.time');
                $qbGames->addOrderBy('field.key1');
                break;
            case 2:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('field.key1');
                $qbGames->addOrderBy('game.time');
                break;
            case 3:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('team.age');
                $qbGames->addOrderBy('game.time');
                $qbGames->addOrderBy('field.key1');
                break;
        }

        // Always get an array even if no records found
        $query = $qbGames->getQuery();
        $items = $query->getResult();

        return $items;
    }
}
?>

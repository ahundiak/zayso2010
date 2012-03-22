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
    /* ========================================================================
     * Returns a list of all the referees associated with a given account
     * and (eventually) project
     */
    public function getOfficialsForAccount($projectId,$accountId)
    {
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('personRegistered');

        $qb->from('ZaysoCoreBundle:Person',       'person');
        $qb->leftJoin('person.accountPersons',    'accountPerson');
        $qb->leftJoin('person.registeredPersons', 'personRegistered');
        $qb->leftJoin('person.projectPersons',    'projectPerson');

        $qb->andWhere($qb->expr()->eq('accountPerson.account',$qb->expr()->literal($accountId)));

        $items = $qb->getQuery()->getResult();
        
        return $items;
    }
    /* ========================================================================
     * Single event stuff
     */
    public function loadEventForId($id)
    {
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

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

        //$qb->addOrderBy('eventPerson.sort'); // Fragile!
        
        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];
        
        return null;
    }
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
     * Schedule Teams query
     */
    public function querySchTeams($search,$games = array())
    {
        // Pull params
        $ages      = $this->getValues($search,'ages'); die('Ages ' . print_r($ages));
        $regions   = $this->getValues($search,'regions');
        $genders   = $this->getValues($search,'genders');
        $projectId = $this->getValues($search,'projectId');

        // Convert keys to ids
        $divisionIds = $this->getDivisionIds($ages,$genders);
        $regionIds   = $this->getRegionIds  ($regions);

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
        if ($regions) $qbGameId->andWhere($qbGameId->expr()->in('teamGameId.org',   $regions));


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
    public function getFieldPickList($search,$games = array())
    {
        // Pull params
        $ages      = $this->getValues($search,'ages');
        $regions   = $this->getValues($search,'regions');
        $genders   = $this->getValues($search,'genders');
        $projectId = $this->getValues($search,'projectId');

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('field');

        $qb->from('Osso2007Bundle:Field','field');

        // if ($projectId) $qb->andWhere($qb->expr()->in('schTeam.projectId',$projectId));

        // if (count($divisionIds)) $qb->andWhere($qb->expr()->in('schTeam.divisionId',$divisionIds));
        if (count($regionIds))   $qb->andWhere($qb->expr()->in('field.unitId',$regionIds));

        $qb->addOrderBy('field.descx');

        $fields = $qb->getQuery()->getResult();
        $fieldPickList = array();
        foreach($fields as $field)
        {
            $fieldPickList[$field->getFieldId()] = $field->getDescx();
        }
        return $fieldPickList;

    }
    public function getDatePickList($projectId)
    {
        // Dates
        $day  = new \DateInterval('P1D');
        $date = new \DateTime('08/01/2011');

        $datePickList = array();
        for($i = 0; $i < 100; $i++)
        {
            $datePickList[$date->format('Ymd')] = $date->format('M d, D');
            $date->add($day);
        }
        return $datePickList;
    }
    public function getTimePickList($projectId)
    {
        $qtr  = new \DateInterval('PT5M');
        $time = new \DateTime('07:00');

        $timePickList = array();
        $timePickList['TBD'] = 'TBD';
        $timePickList['BYE'] = 'BYE';
        for($i = 0; $i < 181; $i++)
        {
            $timePickList[$time->format('Hi')] = $time->format('H:i A');
            $time->add($qtr);
        }
        return $timePickList;
    }
    /* =================================================================================================
     * A new games should always be created with home/away teams
     *
     * What about event_num?
     */
    public function newGame()
    {
        $game = new Event();

        $team = new EventTeam();
        $team->setTeamType('Home');
        $team->setEvent($game);

        $team = new EventTeam();
        $team->setTeamType('Away');
        $team->setEvent($game);

        return $game;
    }

}
?>

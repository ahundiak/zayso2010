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
    /* =========================================================================
     * Everything below was from osso2007
     */
    protected function getSearchParams($search)
    {
        // Master template of all search options
        $searchx = array
        (
            'regionKeys'  => null,
            'regionIds'   => null,
            'ageKeys'     => null,
            'genderKeys'  => null,
            'divisionIds' => null,
            'projectIds'  => null,
            // dates,dateGTE,dateLTE
            // times,timeGTE,timeLTE
        );
        if (isset($search['regions'])) $searchx['regionKeys'] = $search['regions'];
        if (isset($search['ages'   ])) $searchx['ageKeys'   ] = $search['ages'];
        if (isset($search['genders'])) $searchx['genderKeys'] = $search['genders'];

        if (isset($search['projectIds'])) $searchx['projectIds'] = $search['projectIds'];
        if (isset($search['projectId' ])) $searchx['projectIds'] = array($search['projectId']);

        // Maybe process teams???
        //

        // Convert to ids for osso2007
        $searchx['regionIds']   = $this->getRegionIds  ($searchx['regionKeys']);
        $searchx['divisionIds'] = $this->getDivisionIds($searchx['ageKeys'],$searchx['genderKeys']);

        return $searchx;
    }
    protected function getValues($search,$name,$default = null)
    {
        if (!isset($search[$name])) return $default;

        $values = $search[$name];
        if (!is_array($values)) return $values;

        // This will eventually bite me
        if (isset($values['All'])) return $default;
        if (isset($values['A'  ])) return $default;

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
        $ages      = $this->getValues($search,'ages');
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
        $regions = $this->getValues($search,'regions');
        $genders = $this->getValues($search,'genders');

        $sortBy  = $this->getValues($search,'sortBy',1);
        $date1   = $this->getValues($search,'date1');
        $date2   = $this->getValues($search,'date2');

        $projectId = $this->getValues($search,'projectId');

        // Convert keys to ids
        $divisionIds = $this->getDivisionIds($ages,$genders);
        $regionIds   = $this->getRegionIds  ($regions);

        // Build query
        $em = $this->getEntityManager();
        $qbGameId = $em->createQueryBuilder();

        $qbGameId->addSelect('distinct gameGameId.id');

        $qbGameId->from('ZaysoCoreBundle:Event','gameGameId');

        $qbGameId->leftJoin('gameGameId.teams','gameTeamGameId');

        if ($projectId) $qbGameId->andWhere($qbGameId->expr()->in('gameGameId.projectId',$projectId));

        if ($date1) $qbGameId->andWhere($qbGameId->expr()->gte('gameGameId.date',$date1));
        if ($date2) $qbGameId->andWhere($qbGameId->expr()->lte('gameGameId.date',$date2));

      //if (count($divisionIds)) $qbGameId->andWhere($qbGameId->expr()->in('gameTeamGameId.divisionId',$divisionIds));
      //if (count($regionIds))   $qbGameId->andWhere($qbGameId->expr()->in('gameTeamGameId.unitId',    $regionIds));

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
                $qbGames->addOrderBy('field.id');
                break;
            case 2:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('field.id');
                $qbGames->addOrderBy('game.time');
                break;
            case 3:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('team.age');
                $qbGames->addOrderBy('game.time');
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

        // Convert keys to ids
        $divisionIds = $this->getDivisionIds($ages,$genders);
        $regionIds   = $this->getRegionIds  ($regions);

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
    /* =================================================================================================
     * Contain a few em routines
     */
    public function getFieldReference($id)
    {
        return $this->getEntityManager()->getReference('Osso2007Bundle:Field',$id);
    }
    public function getSchTeam($id)
    {
        return $this->getEntityManager()->find('Osso2007Bundle:SchTeam',$id);
    }
    public function getSchTeamReference($id)
    {
        return $this->getEntityManager()->getReference('Osso2007Bundle:SchTeam',$id);
    }
    public function getNextGameNum($projectId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->addSelect($qb->expr()->max('event.eventNum'));

        $qb->from('Osso2007Bundle:Event','event');

        $qb->andWhere($qb->expr()->eq('event.projectId',$projectId));
        $query = $qb->getQuery();
      //$query->setParameter('projectId',$projectId);
        $num = $query->getSingleScalarResult();
        return ++$num;

        // dql just for reference
        $dql = "SELECT MAX(event.eventNum) FROM Zayso\Osso2007Bundle\Entity\Event event WHERE event.projectId = :projectid";
        $dql = "SELECT MAX(event.eventNum) FROM Osso2007Bundle:Event event WHERE event.projectId = :projectId";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('projectId',$projectId);
        $num = $query->getSingleScalarResult();
        return ++$num;
    }
    public function flush()
    {
        return $this->getEntityManager()->flush();
    }
    public function persist($entity)
    {
        return $this->getEntityManager()->persist($entity);
    }
    /* =================================================================================================
     * Region mapping information
     */
    static protected $regionKeyMap = array
    (
        'R0894' =>   1, 'R0391' =>   2, 'R0402' =>   3, 'R0498' =>   4, 'R0557' =>   5, 'R0778' =>   6,
        'R0160' =>   7, 'R0914' =>   8, 'R0916' =>   9, 'R0991' =>  10, 'R1174' =>  11, 'R0890' =>  12,
        'R9990' =>  13, 'R9991' =>  14, 'R9999' =>  15, 'R1467' =>  16, 'R1565' =>  17, 'R1464' =>  18,
        'R9993' =>  19, 'R1062' =>  20, 'R0414' =>  21, 'R9994' =>  23, 'R0773' =>  24, 'R9995' =>  25,
        'R9996' =>  26, 'R9980' =>  27, 'R0622' =>  28, 'R0128' =>  29, 'R9997' =>  30, 'R9998' =>  31,
        'R0649' => 448, 'R1011' => 643, 'R1373' => 792, 'R1096' => 793,
    );
    static protected $regionMap = array
    (
       1 => array('desc' => 'R0894 Monrovia',       'key' => 'R0894', ),
       2 => array('desc' => 'R0391 McMinnville',    'key' => 'R0391', ),
       3 => array('desc' => 'R0402 Ft Payne',       'key' => 'R0402', ),
       4 => array('desc' => 'R0498 Madison',        'key' => 'R0498', ),
       5 => array('desc' => 'R0557 Lincoln County', 'key' => 'R0557', ),
       6 => array('desc' => 'R0778 Arab',           'key' => 'R0778', ),
       7 => array('desc' => 'R0160 Huntsville',     'key' => 'R0160', ),
       8 => array('desc' => 'R0914 East Limestone', 'key' => 'R0914', ),
       9 => array('desc' => 'R0916 Athens',         'key' => 'R0916', ),
      10 => array('desc' => 'R0991 Sewanee',        'key' => 'R0991', ),
      11 => array('desc' => 'R1174 NEMC',           'key' => 'R1174', ),
      12 => array('desc' => 'R0890 Lewisburg',      'key' => 'R0890', ),
      13 => array('desc' => 'R9990 COHPAR',         'key' => 'R9990', ),
      14 => array('desc' => 'R9991 Excalibur',      'key' => 'R9991', ),
      15 => array('desc' => 'R9999 Unknown',        'key' => 'R9999', ),
      16 => array('desc' => 'R1467 Blue Water',     'key' => 'R1467', ),
      17 => array('desc' => 'R1565 Ardmore',        'key' => 'R1565', ),
      18 => array('desc' => 'R1464 Chapel Hill',    'key' => 'R1464', ),
      19 => array('desc' => 'R9993 Valley',         'key' => 'R9993', ),
      20 => array('desc' => 'R1062 Albertville',    'key' => 'R1062', ),
      21 => array('desc' => 'R0414 Cullman',        'key' => 'R0414', ),
      23 => array('desc' => 'R9994 Madison Academy','key' => 'R9994', ),
      24 => array('desc' => 'R0773 Hartselle',      'key' => 'R0773', ),
      25 => array('desc' => 'R9995 ESL',            'key' => 'R9995', ),
      26 => array('desc' => 'R9996 Oneonta',        'key' => 'R9996', ),
      27 => array('desc' => 'R9980 Area 5C',        'key' => 'R9980', ),
      28 => array('desc' => 'R0622 Sand Rock',      'key' => 'R0622', ),
      29 => array('desc' => 'R0128 Knoxville',      'key' => 'R0128', ),
      30 => array('desc' => 'R9997 Huntsville FC',  'key' => 'R9997', ),
      31 => array('desc' => 'R9998 United',         'key' => 'R9998', ),
     448 => array('desc' => 'R0649 Blount County',  'key' => 'R0649', ),
     643 => array('desc' => 'R1011 Guntersville',   'key' => 'R1011', ),
     792 => array('desc' => 'R1373 Scotsborro',     'key' => 'R1373', ),
     793 => array('desc' => 'R1096 Killen',         'key' => 'R1096', ),
    );
    static public function getRegionIdForKey($key)
    {
        if (isset(self::$regionKeyMap[$key])) return self::$regionMap[$key];
        return null;
    }
    static public function getRegionKey($id)
    {
        if (isset(self::$regionMap[$id])) return self::$regionMap[$id]['key'];
        return null;
    }
    static public function getRegionDesc($id)
    {
        if (isset(self::$regionMap[$id])) return self::$regionMap[$id]['desc'];
        return null;
    }
    protected function getRegionIds($regions)
    {
        $ids = array();
        if (!count($regions)) return $ids;
        foreach($regions as $region)
        {
            if (isset(self::$regionKeyMap[$region]))
            {
                $id = self::$regionKeyMap[$region];
                $ids[$id] = $id;
            }
        }
        return $ids;
    }
    /* ===========================================================================
     * Team Person types
     */
    const TYPE_TEAM_HOME = 1;
    const TYPE_TEAM_AWAY = 2;

    const TYPE_DIV_COORD   = 31;
    const TYPE_HEAD_COACH  = 16;
    const TYPE_ASST_COACH  = 17;
    const TYPE_MANAGER     = 18;
    const TYPE_TEAM_PARENT = 18;
    const TYPE_ADULT_REF   = 19;
    const TYPE_YOUTH_REF   = 20;
    const TYPE_GAME_SCHEDULER    = 34;
    const TYPE_REFEREE_SCHEDULER = 35;
    const TYPE_ZADM              = 27;


    static protected $pickList = array(
        self::TYPE_HEAD_COACH  => 'Head Coach',
        self::TYPE_ASST_COACH  => 'Asst Coach',
        self::TYPE_TEAM_PARENT => 'Team Parent',
        self::TYPE_DIV_COORD   => 'Div Coordinator',
        self::TYPE_ADULT_REF   => 'Adult Referee',
        self::TYPE_YOUTH_REF   => 'Youth Referee',
        self::TYPE_GAME_SCHEDULER    => 'Game Scheduler',
        self::TYPE_REFEREE_SCHEDULER => 'Referee Scheduler',
        self::TYPE_ZADM              => 'Zayso Administrator',
    );
    /* ========================================================================
     * Division Stuff
     */
    static protected $divisionPickList = array
    (
        '1'  => 'U06B', '2' => 'U06G', '3' => 'U06C',
        '4'  => 'U08B', '5' => 'U08G', '6' => 'U08C',
        '7'  => 'U10B', '8' => 'U10G', '9' => 'U10C',
        '10' => 'U12B','11' => 'U12G','12' => 'U12C',
        '13' => 'U14B','14' => 'U14G','15' => 'U14C',
        '16' => 'U16B','17' => 'U16G','18' => 'U16C',
        '19' => 'U19B','20' => 'U19G','21' => 'U19C',
        '22' => 'U05B','23' => 'U05G','24' => 'U05C',
        '25' => 'U07B','26' => 'U07G','27' => 'U07C',
    );
    static protected $divisionAgeGenderMap = array
    (
      'U05' => array('B' => 23, 'G' => 23, 'C' => 24),
      'U06' => array('B' =>  1, 'G' =>  2, 'C' =>  3),
      'U07' => array('B' => 25, 'G' => 26, 'C' => 27),
      'U08' => array('B' =>  4, 'G' =>  5, 'C' =>  6),
      'U10' => array('B' =>  7, 'G' =>  8, 'C' =>  9),
      'U12' => array('B' => 10, 'G' => 11, 'C' => 12),
      'U14' => array('B' => 13, 'G' => 14, 'C' => 15),
      'U16' => array('B' => 16, 'G' => 17, 'C' => 18),
      'U19' => array('B' => 19, 'G' => 20, 'C' => 21),
    );
    static public function getGenderKey($id)
    {
        if (isset(self::$divisionPickList[$id])) return substr(self::$divisionPickList[$id],3,1);
        return null;
    }
    static public function getAgeKey($id)
    {
        if (isset(self::$divisionPickList[$id])) return substr(self::$divisionPickList[$id],0,3);
        return null;
    }
    static public function getDivKey($id)
    {
        if (isset(self::$divisionPickList[$id])) return self::$divisionPickList[$id];
        return null;
    }
    static public function getDivisionDesc($id)
    {
        if (isset(self::$divisionPickList[$id])) return self::$divisionPickList[$id];
        return null;
    }
    protected function getDivisionIds($ages = array(),$genders = array())
    {
        $ids = array();

        // Check if you want everything
        if (!count($ages) && !count($genders)) return $ids;

        // Handle ages but no gender
        if (count($ages) && !count($genders))
        {
            foreach($ages as $age)
            {
                if (isset(self::$divisionAgeGenderMap[$age]))
                {
                    $genders = self::$divisionAgeGenderMap[$age];
                    foreach($genders as $gender => $id)
                    {
                        $ids[$id] = $id;
                    }
                }
            }
            return $ids;
        }
        // Handle gender but no ages

        // Handle both
        foreach($ages as $age)
        {
            foreach($genders as $gender)
            {
                if (isset(self::$divisionAgeGenderMap[$age][$gender]))
                {
                    $id = self::$divisionAgeGenderMap[$age][$gender];
                    $ids[$id] = $id;
                }
            }
        }
        return $ids;
    }
}
?>

<?php
namespace Zayso\Osso2007Bundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\Osso2007Bundle\Component\Debug;

//use Zayso\ZaysoBundle\Entity\Project;
//use Zayso\ZaysoBundle\Entity\ProjectSeqn;
//use Zayso\ZaysoBundle\Entity\ProjectPerson;

//use Zayso\ZaysoBundle\Entity\Person;
//use Zayso\ZaysoBundle\Entity\PersonRegistered;
//use Zayso\ZaysoBundle\Entity\AccountPerson;

/* =========================================================================
 * The repository, really a service
 */
class GameRepository extends EntityRepository
{
    protected function getValues($search,$name,$default = null)
    {
        if (!isset($search[$name])) return $default;

        $values = $search[$name];
        if (!is_array($values)) return $values;

        if (isset($values['All'])) return $default;

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
        $ages    = $this->getValues($search,'ages');
        $regions = $this->getValues($search,'regions');
        $genders = $this->getValues($search,'genders');

        // Convert keys to ids
        $divisionIds = $this->getDivisionIds($ages,$genders);
        $regionIds   = $this->getRegionIds  ($regions);

        $projectId = $this->getValues($search,'projectId');

        // Add in anyting from the games themselves
        foreach($games as $game)
        {
            foreach($game->getGameTeams() as $team)
            {
                if (count($divisionIds))
                {
                    $divId = $team->getDivisionId();
                    $divisionIds[$divId] = $divId;
                }
                if (count($regionIds))
                {
                    $regionId = $team->getUnitId();
                    $regionIds[$regionId] = $regionId;
                }
            }
        }
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('schTeam');

        $qb->from('Osso2007Bundle:SchTeam','schTeam');

        if ($projectId) $qb->andWhere($qb->expr()->in('schTeam.projectId',$projectId));

        if (count($divisionIds)) $qb->andWhere($qb->expr()->in('schTeam.divisionId',$divisionIds));
        if (count($regionIds))   $qb->andWhere($qb->expr()->in('schTeam.unitId',    $regionIds));

        $qb->addOrderBy('schTeam.descShort');

        $teams = $qb->getQuery()->getResult();
        return $teams;

    }
    public function querySchTeamsPickList($search,$games = array())
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

        // Build query
        $em = $this->getEntityManager();
        $qbGameId = $em->createQueryBuilder();

        $qbGameId->addSelect('distinct gameGameId.eventId');

        $qbGameId->from('Osso2007Bundle:Event','gameGameId');

        // $qbGameId->leftJoin('gameGameId.gameTeams','gameTeamGameId');

        if ($projectId) $qbGameId->andWhere($qbGameId->expr()->in('gameGameId.projectId',$projectId));

        if ($date1) $qbGameId->andWhere($qbGameId->expr()->gte('gameGameId.eventDate',$date1));
        if ($date2) $qbGameId->andWhere($qbGameId->expr()->lte('gameGameId.eventDate',$date2));

        //if (count($ages))    $qbGameId->andWhere($qbGameId->expr()->in('gameTeamGameId.age',   $ages));
        //if (count($regions)) $qbGameId->andWhere($qbGameId->expr()->in('gameTeamGameId.orgKey',$regions));
        //if (count($genders)) $qbGameId->andWhere($qbGameId->expr()->in('gameTeamGameId.gender',$genders));

        //$gameIds = $qbGameId->getQuery()->getArrayResult();
        //Debug::dump($gameIds);
        //return $gameIds;

        // Games
        $qbGames = $em->createQueryBuilder();

        $qbGames->addSelect('game');
      //$qbGames->addSelect('gameTeam');
      //$qbGames->addSelect('person');

        $qbGames->from('Osso2007Bundle:Event','game');

      //$qbGames->leftJoin('game.gameTeams','gameTeam');
      //$qbGames->leftJoin('game.persons',  'person');

        $qbGames->andWhere($qbGames->expr()->in('game.eventId',$qbGameId->getDQL()));
/*
        switch($sortBy)
        {
            case 1:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('game.time');
                $qbGames->addOrderBy('game.fieldKey');
                break;
            case 2:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('game.fieldKey');
                $qbGames->addOrderBy('game.time');
                break;
            case 3:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('game.age');
                $qbGames->addOrderBy('game.time');
                break;
        }
 *
 */
        // Always get an array even if no records found
        $query = $qbGames->getQuery();
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
            $id = self::$regionKeyMap[$region];
            $ids[$id] = $id;
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

        // Handle gender but no ages

        // Handle both
        foreach($ages as $age)
        {
            foreach($genders as $gender)
            {
                $id = self::$divisionAgeGenderMap[$age][$gender];
                $ids[$id] = $id;
            }
        }
        return $ids;
    }
}
?>

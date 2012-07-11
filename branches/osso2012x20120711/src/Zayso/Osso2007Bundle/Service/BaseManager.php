<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\Osso2007Bundle\Service;

use Zayso\Osso2007Bundle\Component\Debug;

class BaseManager
{
    protected $em = null;
    
    public function getEntityManager() { return $this->em; }

    public function __construct($em)
    {
        $this->em = $em;
    }
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
        if (isset(self::$regionKeyMap[$key])) return self::$regionKeyMap[$key];
        $region = (int)$key;
        if (!$region) return null;

        $key = sprintf('R%04u',$region);
        if (isset(self::$regionKeyMap[$key])) return self::$regionKeyMap[$key];

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

<?php
namespace Zayso\S5GamesBundle\Import;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event        as Game;
use Zayso\CoreBundle\Entity\EventTeam    as GameTeamRel;
use Zayso\CoreBundle\Entity\EventPerson  as GameReferee;
use Zayso\CoreBundle\Entity\ProjectField as Field;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class ScheduleImport extends ExcelBaseImport
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
        parent::__construct($manager->getEntityManager());
    }
    protected function newTeam($div,$type = null, $key = null, $org = null)
    {
        $team = new Team();
        $team->setProject($this->project);
        $team->setType($type);
        $team->setStatus ('Active');
        $team->setSource ('import');
        $team->setKey    ($key);
        $team->setOrg    ($org);
        $team->setAge    (substr($div,0,3));
        $team->setGender (substr($div,3,1));
       
        return $team;
    }
    // U10B PP A2
    public function processPoolTeam($key)
    {
        // Verify it is a pool play game
        if (!$key) return null;
        if (substr($key,5,2) != 'PP') return null;
        
        $manager = $this->manager;
        
        $team = $manager->loadPoolTeamForKey($this->projectId,$key);
        
        if ($team) return $team;
        
        $parts  = explode(' ',$key);
        $div    = trim($parts[0]);
        
        $team = $this->newTeam($div,'pool',$key);
        
        $manager->persist($team);
        
        return $team;
    }
    // R0894-U10C Griswell
    public function processPhyTeam($key)
    {
        if (!$key) return null;
        $manager = $this->manager;
        
        $team = $manager->loadPhyTeamForKey2($this->projectId,$key);
        
        if ($team) return $team;
        die('No phy team for ' . $key . "\n");
        
        $prefix = explode(' ',$key);
        $parts  = explode('-',$prefix[0]);
        $region = trim($parts[0]);
        $div    = trim($parts[1]);
        
        $org = $manager->getRegionReference('AYSO' . $region);
        
        $team = $this->newTeam($div,'physical',$key,$org);
        
        $manager->persist($team);
        
        return $team;
    }
    protected function processTeamRow($row)
    {
        if (!isset($row[1]) || !trim($row[1])) return;
        $schTeamKey  = trim($row[0]);
        $poolTeamKey = trim($row[1]);
        
        $schTeamKeyx = substr($poolTeamKey,0,5) . str_replace(' ','',$schTeamKey);
        
        $phyTeam  = $this->processPhyTeam($schTeamKeyx);
        $phyTeamKey = $phyTeam->getKey();
        
      //echo sprintf("%s:%s:%s\n",$poolTeamKey,$schTeamKey,$phyTeam->getKey());
        
        $poolTeam = $this->processPoolTeam($poolTeamKey);
        
        $poolTeam->setParent($phyTeam);
        $poolTeam->setOrg($phyTeam->getOrg());
        
        // Maybe do pool team description as well?
        $desc = substr($poolTeamKey,8,2) . ' ' . $phyTeamKey;
        $poolTeam->setDesc($desc);
        
        $this->teams[$schTeamKey] = $poolTeam;
    }
    public function processField($key)
    {
        $key = 'F' . $key;
        
        if (isset($this->fields[$key])) return $this->fields[$key];
        
        $manager = $this->manager;
        $field = $manager->loadFieldForKey($this->projectId,$key);
        if ($field) 
        {   
            $this->fields[$key] = $field;
            return $field;
        }
        $field = new Field();
        $field->setProject($this->project);
        $field->setKey($key);
        
        $manager->persist($field);
        
        $this->fields[$key] = $field;   

        return $field;
        
    }
    protected function addReferee($game,$type)
    {
        $gameReferee = new GameReferee();
        
        $gameReferee->setType($type);
        $gameReferee->setEvent($game);
        $gameReferee->setProtected(true);
        
        $game->addPerson($gameReferee);
        
        $this->manager->persist($gameReferee);
        
        return $gameReferee;
    }
    protected function addTeamToGame($game,$div,$type,$poolTeam,$schTeamKey)
    {
        if ($poolTeam) $gameTeam = $poolTeam;
      /*{
            $gameTeam->setParent($poolTeam);
            
            $poolTeamKey = $poolTeam->getKey();
            
            if ($poolTeam->getParent()) 
            {
                $phyTeamKey = $poolTeam->getParent()->getKey();
                $gameTeamDesc = substr($poolTeamKey,8,2) . ' ' . $phyTeamKey;
            }
            else $gameTeamDesc = $poolTeamKey;
            
            $gameTeam->setDesc($gameTeamDesc);
        } */
        else 
        {
            $gameTeam = $this->newTeam($div,'playoff');
            $gameTeam->setKey ($schTeamKey);
            $gameTeam->setDesc($schTeamKey);
            $this->manager->persist($gameTeam);
        }
        $gameTeamRel = new GameTeamRel();
        $gameTeamRel->setGame($game);
        $gameTeamRel->setType($type);
        $gameTeamRel->setTeam($gameTeam);
        
        $this->manager->persist($gameTeamRel);
      
        return $gameTeam;
    }
    protected function processGameRow($row)
    {
        if (!isset($row[2]) || !trim($row[2])) return;
        
        $gameNum = (int)trim($row[2]);
        if (!$gameNum) return;
        
        $date     = trim($row[3]);
        $time     = trim($row[4]);
        $fieldKey = trim($row[5]);
        $pool     = trim($row[6]);
        $homeSchTeamKey = trim($row[7]);
        $awaySchTeamKey = trim($row[8]);
        
        switch($date)
        {
            case 'Fri': $date = '20120615'; break;
            case 'Sat': $date = '20120616'; break;
            case 'Sun': $date = '20120617'; break;
            default:
                die('Date ' . $date);
        }
        $time  = $this->processTime($time);
        $field = $this->processField($fieldKey);
        
        // Pool teams
        if (isset($this->teams[$homeSchTeamKey])) $homePoolTeam = $this->teams[$homeSchTeamKey];
        else                                      $homePoolTeam = null;
        if (isset($this->teams[$awaySchTeamKey])) $awayPoolTeam = $this->teams[$awaySchTeamKey];
        else                                      $awayPoolTeam = null;
        
        // Check
        if (($homePoolTeam && !$awayPoolTeam) || (!$homePoolTeam && $awayPoolTeam))
        {
            echo sprintf("*** Have one pool team but not two %s %s\n",$homeSchTeamKey,$awaySchTeamKey);
        }
        $div = substr($pool,0,4);
        
        // Do the games
        $game = $this->manager->loadEventForNum($this->projectId,$gameNum);
        
        if (!$game)
        {
            $game = new Game();
            $game->setProject($this->project);
            $game->setNum ($gameNum);
            $game->setPool($pool);

            $this->addTeamToGame($game,$div,'Home',$homePoolTeam,$homeSchTeamKey);
            $this->addTeamToGame($game,$div,'Away',$awayPoolTeam,$awaySchTeamKey);
   
            $this->addReferee($game,GameReferee::TypeCR);
            $this->addReferee($game,GameReferee::TypeAR1);
            $this->addReferee($game,GameReferee::TypeAR2);
            
            $this->manager->persist($game);
        }
        $game->setDate ($date);
        $game->setTime ($time);
        $game->setField($field);
        
      //echo sprintf("Game %02u %s %s %s %s %s\n",$gameNum,$date,$time,$field->getKey(),$homeSchTeamKey,$awaySchTeamKey);
        
    }
    protected function processDiv($reader,$div)
    {
        $sheet = $reader->getSheetByName($div);
        if (!$sheet) return;
        $rows = $sheet->toArray();
        
        $this->teams = array();
        
        // Loop once for team
        foreach($rows as $row)
        {
            $this->processTeamRow($row);
        }

        // Loop once for game
        foreach($rows as $row)
        {
            $this->processGameRow($row);
        }
    }
    /* =================================================================
     * Merge this back into the base class after some testing
     */
    public function process($params = array())
    {
        $this->fields = array();
        
        // For tracking changes
        $this->getEntityManager()->getEventManager()->addEventListener(
            array(Events::postUpdate, Events::postRemove, Events::postPersist),
            $this);

        // Often have a project
        if (isset($params['projectId']) && $params['projectId']) $projectId = $params['projectId'];
        else                                                     $projectId = 62;
        
        $projectId = 62;
        
        if ($projectId)
        {
            $this->projectId = $projectId;
            $this->project = $this->manager->getProjectReference($projectId);
        }

        // Need an input file
        if (isset($params['inputFileName'])) $inputFileName = $params['inputFileName'];
        else
        {
            $this->errors[] = 'No inputFileName';
            return $this->getResults();
        }
        $this->inputFileName = $inputFileName;

        // Client file name for web processing
        if (isset($params['clientFileName'])) $this->clientFileName = $params['clientFileName'];
        else                                  $this->clientFileName = $this->inputFileName;
        
        $reader = $this->excel->load($inputFileName);

        $divs = array('U10 Boys','U10 Girls','U12 Boys','U12 Girls','U14 Boys','U14 Girls','U19 Boys','U19 Girls');
        foreach($divs as $div)
        {
            $this->processDiv($reader,$div);
        }

        // Finish up
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear(); // Need for multiple files

        return $this->getResults();
    }
}
?>

<?php
namespace Zayso\AreaBundle\Component\Import;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event        as Game;
use Zayso\CoreBundle\Entity\EventTeam    as GameTeamRel;
use Zayso\CoreBundle\Entity\EventPerson  as GameReferee;
use Zayso\CoreBundle\Entity\ProjectField as Field;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class SendoffImport extends ExcelBaseImport
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
        
        $team = $manager->loadPhyTeamForKey($this->projectId,$key);
        
        if ($team) return $team;
        
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
        if (!isset($row[10]) || !trim($row[10]) || trim($row[10]) == 'Pool') return;
        $poolTeamKey = trim($row[10]);
        $schTeamKey  = trim($row[11]);
        $phyTeamKey  = trim($row[12]);
        
        echo sprintf("%s %s %s\n",$poolTeamKey,$phyTeamKey,$schTeamKey);
    
        $poolTeam = $this->processPoolTeam($poolTeamKey);
        $phyTeam  = $this->processPhyTeam ($phyTeamKey);
        
        $poolTeam->setParent($phyTeam);
        
        $this->teams[$schTeamKey] = $poolTeam;
    }
    public function processField($key)
    {
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
        $gameTeam = $this->newTeam($div,'game');
        if ($poolTeam) 
        {
            $gameTeam->setParent($poolTeam);
            $gameTeam->setDesc  ($poolTeam->getKey());
        }
        else $gameTeam->setKey($schTeamKey);
        
       $gameTeamRel = new GameTeamRel();
       $gameTeamRel->setGame($game);
       $gameTeamRel->setType($type);
       $gameTeamRel->setTeam($gameTeam);
        
       $this->manager->persist($gameTeamRel);
       $this->manager->persist($gameTeam);
      
       return $gameTeam;
    }
    protected function processGameRow($row)
    {
        if (!isset($row[0]) || !trim($row[0])) return;
        
        $gameNum = (int)trim($row[0]);
        if (!$gameNum) return;
        
        $date     = trim($row[1]);
        $fieldKey = trim($row[3]);
        $time     = trim($row[4]);
        $homeSchTeamKey = trim($row[5]);
        $awaySchTeamKey = trim($row[7]);
        
        $date  = $this->processDate($date);
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
        // Determine the pool
        $pool = null;
        if ($homePoolTeam) $pool = substr($homePoolTeam->getKey(),0,9);
        else
        {
            $pool = substr($homeSchTeamKey,0,7);
            $type = substr($pool,5,2);
            switch($type)
            {
                case 'CM':
                case 'FM':
                    break;
                default:
                    echo '*** Team Key ??? ' . $homeSchTeamKey . "\n";
                    return;
            }
        }
      //echo 'Pool ' . $pool . "\n";
      //return;
        $div = substr($pool,0,4);
        
        // Do the games
        $game = $this->manager->loadEventForNum($this->projectId,$gameNum);
        
        if (!$game)
        {
            $game = new Game();
            $game->setProject($this->project);
            $game->setNum ($gameNum);
            $game->setPool($pool);

            $homeTeam = $this->addTeamToGame($game,$div,'Home',$homePoolTeam,$homeSchTeamKey);
            $awayTeam = $this->addTeamToGame($game,$div,'Away',$awayPoolTeam,$awaySchTeamKey);
   
            $this->addReferee($game,GameReferee::TypeCR);
            $this->addReferee($game,GameReferee::TypeAR1);
            $this->addReferee($game,GameReferee::TypeAR2);
            
            $this->manager->persist($game);
        }
        $game->setDate ($date);
        $game->setTime ($time);
        $game->setField($field);
        
        echo sprintf("Game %02u %s %s %s %s %s\n",$gameNum,$date,$time,$field->getKey(),$homeSchTeamKey,$awaySchTeamKey);
        
    }
    protected function processAge($reader,$age)
    {
        $sheet = $reader->getSheetByName($age);
        if (!$sheet) return;
        $rows = $sheet->toArray();
        
        $this->teams  = array();
        
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
        else                                                     $projectId = 79;

        if ($projectId)
        {
            $this->projectId = $projectId;
            $this->project = $this->getEntityManager()->getReference('ZaysoCoreBundle:Project',$projectId);
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

        $ages = array('U10','U10x');
        foreach($ages as $age)
        {
            $this->processAge($reader,$age);
        }
        // if (isset($params['sheetName'])) $sheetName = $params['sheetName'];
        // else                             $sheetName = 'Schedule';
        
        // Process it
        // $this->processInputFile($inputFileName,$sheetName);

        // Finish up
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear(); // Need for multiple files

        return $this->getResults();
    }
}
?>

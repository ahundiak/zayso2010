<?php

namespace Zayso\NatGamesBundle\Component\Import;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event        as Game;
use Zayso\CoreBundle\Entity\EventTeam    as GameTeamRel;
use Zayso\CoreBundle\Entity\EventPerson  as GameReferee;
use Zayso\CoreBundle\Entity\ProjectField as Field;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class Schedule2012Import extends ExcelBaseImport
{
    protected $manager = null;
    protected $orgs  = null;
    protected $teams = null;
    
    public function __construct($manager,$projectId = 52)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
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
    public function processField($key)
    {
        if (!$key) return null;
        
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
    /* =============================================================
     * Need when adding a new region
     */
    protected function processSection($section)
    {
        $key = sprintf('AYSOS%02u',$section);
        if (isset($this->orgs[$key])) return $this->orgs[$key];
        
        $org = $this->manager->loadOrgForKey($key);
        if ($org)
        {
           // Got it
            $this->orgs[$key] = $org;
            return $org;
        }
        
        // Make one
        echo $key . "\n";die();
        
    }
    /* =============================================================
     * Need when adding a new region
     */
    protected function processArea($section,$area)
    {
        $key = sprintf('AYSOA%02u%s',$section,$area);
        if (isset($this->orgs[$key])) return $this->orgs[$key];
        
        $org = $this->manager->loadOrgForKey($key);
        if ($org)
        {
           // Got it
            $this->orgs[$key] = $org;
            return $org;
        }
        
        // Make one
        echo $key . "\n";
        
        $sectionEntity = $this->processSection($section);
        $desc = sprintf('AYSO Area %02u%s',$section,$area);
        $org = new Org();
        $org->setId($key);
        $org->setParent($sectionEntity);
        $org->setDesc1($desc);
        $org->setStatus('Active');
        
        $this->manager->persist($org);
        
        $this->orgs[$key] = $org;
        return $org;
        
    }
    /* =============================================================
     * See if have a region
     */
    protected function processRegion($section,$area,$region)
    {
        $key = sprintf('AYSOR%04u',$region);
        if (isset($this->orgs[$key])) return $this->orgs[$key];
        
        $org = $this->manager->loadOrgForKey($key);
        if ($org)
        {
            // Maybe check section and area?
            $desc = $org->getDesc2();
            $sectionx = (int)substr($desc,1,2);
            $areax    =      substr($desc,3,1);
            if ($section != $sectionx)
            {
                //echo sprintf("Mismatch section %s %d %s\n",$desc,$section,$area);
            }
            if ($area != $areax)
            {
                //echo sprintf("Mismatch area    %s %d %s\n",$desc,$section,$area);
            }
            // Got it
            $this->orgs[$key] = $org;
            return $org;
        }
        $areaEntity = $this->processArea($section,$area);
        
        $desc1 = sprintf('AYSO Region %04u',$region);
        $desc2 = sprintf('A%02u%s-R%04u TBD, %s',$section,$area,$region,$areaEntity->getState());

//      echo $desc2 . "\n";
        
        $org = new Org();
        $org->setId($key);
        $org->setParent($areaEntity);
        $org->setState ($areaEntity->getState());
        
        $org->setDesc1($desc1);
        $org->setDesc2($desc2);
        
        $org->setStatus('Active');
        
        $this->manager->persist($org);
        
        $this->orgs[$key] = $org;
        
        return $org;
        
        $this->orgs[$key] = true;
        
        echo sprintf("A%02u%s-R%04u\n",$section,$area,$region);
        return null;
    }
    /* =============================================================
     * Individual team row
     */
    protected function processPhyTeam($org,$region,$div)
    {
        // Basic Key
        $age    = substr($div,0,3);
        $gender = substr($div,3,1);
        
        $key = sprintf('R%04u-%s%sR',$region,$age,$gender);
        
        // Should not have dups
        if (isset($this->phyTeams[$key]))
        {
            echo "Dup Team Key $key \n";
            return;
        }
        // See if exists
        $team = $this->manager->loadPhyTeamForKey($this->projectId,$key);
        if ($team)
        {
            $this->phyTeams[$key] = $team; // echo $key . "\n";
            return $team;
        }
        // Create
        $team = $this->newTeam($age . $gender, 'physical', $key, $org);
        $team->setLevel('regular');
        
        $this->manager->persist($team);
        
        $this->phyTeams[$key] = $team;
        
        //echo 'Added phy team ' . $key . "\n";
        
        return $team;
    }
    protected function processTeam($gid,$key3)
    {
        // VACant teams
        if (!$key3) return;
        if (strpos($key3,'VAC') !== FALSE) return;
        if (strpos($key3,'BYE') !== FALSE) return;
        
        $pools = array(
            'A1','A2','A3','A4','A5','A6','A7','A8',
            'B1','B2','B3','B4','B5','B6','B7','B8',
            'C1','C2','C3','C4','C5','C6','C7','C8',
            'D1','D2','D3','D4','D5','D6','D7','D8',
        );
        if (!in_array($gid,$pools)) return;
        
        $divs = array(
            'BU10' => 'U10B','BU12' => 'U12B','BU14' => 'U14B','BU16' => 'U16B','BU19' => 'U19B',
            'GU10' => 'U10G','GU12' => 'U12G','GU14' => 'U14G','GU16' => 'U16G','GU19' => 'U19G',
        );
        $parts = explode('/',$key3);
        if (count($parts) == 4)
        {
            $key4 = $parts[0];
            $section = (int)$parts[1];
            $area    = $parts[2];
            $region  = (int)$parts[3];
            
            $div = $divs[substr($key4,0,4)];
        }
        else
        {
            if (strpos($key3,'BYE') !== false) return;
            
            die('Not enough parts ' . $gid . ' ' . $key3 . "\n");
        }
        
        // Region
        $org = $this->processRegion($section,$area,$region);
        
        // Physical team
        $phyTeam = $this->processPhyTeam($org,$region,$div);
        
        // Pool Team
        $desc = sprintf('%s A%02u%s-R%04u-%s',$gid,$section,$area,$region,$div);
        $key  = sprintf('%s PP %s',$div,$gid);
        
        // See if exists
        $team = $this->manager->loadPoolTeamForKey($this->projectId,$key);
        if ($team)
        {
            $this->poolTeams[$key3] = $team; // echo $key . "\n";
            return $team;
        }
        // Create
        $age    = substr($div,0,3);
        $gender = substr($div,3,1);
        
        $team = $this->newTeam($age . $gender, 'pool', $key, $org);
        $team->setLevel('regular');
        $team->setParent($phyTeam);
        $team->setKey3($key3);
        $team->setKey4($key4);
        
        $team->setDesc1($desc);
         
        $this->manager->persist($team);
        
        $this->poolTeams[$key3] = $team;
        
    }
    protected function processTeamRowLeft($row)
    {
        //print_r($row);
        
        $gid = trim($row[ 0]);
        $key = trim($row[ 1]);
        $this->processTeam($gid,$key);
        return;
        
    }
    protected function processTeamRowRight($row)
    {
        $gid = trim($row[16]);
        $key = trim($row[17]);
        $this->processTeam($gid,$key);
        return;
   }
    protected function processGameRowLeft($sheetName,$row)
    {
        $seq = (int)trim($row[0]);
        $num =      trim($row[1]);
        
        $date  = trim($row[ 2]);
        $time  = trim($row[ 3]);
        
        $field = trim($row[ 5]);
        
        $home  = trim($row[ 7]);
        $away  = trim($row[11]);
        
        $this->processGame('U' . $sheetName,$seq,$num,$date,$time,$field,$home,$away);
        
        return;
        
   }
   protected function processGameRowRight($sheetName,$row)
   {
        $seq = (int)trim($row[16]);
        $num =      trim($row[17]);
        
        $date  = trim($row[18]);
        $time  = trim($row[19]);
        
        $field = trim($row[21]);
        
        $home  = trim($row[23]);
        $away  = trim($row[27]);
        
        $this->processGame('U' . $sheetName,$seq,$num,$date,$time,$field,$home,$away);
        
        return;
        
   }
    protected function processNewPoolTeam($div,$key)
    {
        // E1 G3
        if (strlen($key) != 2) return $key;
        
        $key = $key . ' ' . $div;
        
        if (isset($this->poolTeams[$key])) return $key;
        
        // See if exists
        $team = $this->manager->loadPoolTeamForKey($this->projectId,$key);
        if ($team)
        {
            $this->poolTeams[$key] = $team; echo $key . "\n";
            return $key;
        }
           
        // Create
        $team = $this->newTeam($div, 'pool', $key);
        $team->setLevel('regular');
        $team->setDesc1($key);
         
        $this->manager->persist($team);
        
        $this->poolTeams[$key] = $team;
        
        return $key;
    }
    protected function processGame($div,$seq,$num,$date,$time,$fieldKey,$homeTeamKey,$awayTeamKey)
    {
        if ($seq < 10) return;
        
        if (strpos($homeTeamKey,'VAC') !== FALSE) return;
        if (strpos($awayTeamKey,'VAC') !== FALSE) return;
        
        // Need to sleep on bye
        if (strpos($homeTeamKey,'BYE') !== false) return;
        if (strpos($awayTeamKey,'BYE') !== false) return;
        
        // Date time
        $date = $this->processDate($date);
        $time = $this->processTime($time);
       
        // Field
        $field = $this->processField($fieldKey);
        if (!$field)
        {
            die('No field for ' . $seq);
        }
        $homeTeamKey = $this->processNewPoolTeam($div,$homeTeamKey);
        $awayTeamKey = $this->processNewPoolTeam($div,$awayTeamKey);
        
        // Teams
        if (isset($this->poolTeams[$homeTeamKey])) $homeTeam = $this->poolTeams[$homeTeamKey];
        else                                       $homeTeam = null;
       
        if (isset($this->poolTeams[$awayTeamKey])) $awayTeam = $this->poolTeams[$awayTeamKey];
        else                                       $awayTeam = null;

        // Make sure have both or neither
        // Bye will tirgger this
        if ($homeTeam || $awayTeam)
        {
            if ($homeTeam) $pool = sprintf('%s PP %s',$div,substr($homeTeam->getDesc(),0,1)); 
            if ($awayTeam) $pool = sprintf('%s PP %s',$div,substr($awayTeam->getDesc(),0,1)); 
            
        }
        else
        {   
            $pool = $div . ' ' . substr($num,3);
            $homeTeamKey = $div . ' ' . $homeTeamKey;
            $awayTeamKey = $div . ' ' . $awayTeamKey;
        }
        
        // Do the games
        $game = $this->manager->loadEventForNum($this->projectId,$seq);
        
        if (!$game)
        {
            $game = new Game();
            $game->setProject($this->project);
            $game->setNum ($seq);
            $game->setPool($pool);
            $game->set('numx',$num);
            
            $this->addTeamToGame($game,$div,'Home',$homeTeam,$homeTeamKey);
            $this->addTeamToGame($game,$div,'Away',$awayTeam,$awayTeamKey);
   
            $this->addReferee($game,GameReferee::TypeCR);
            $this->addReferee($game,GameReferee::TypeAR1);
            $this->addReferee($game,GameReferee::TypeAR2);
            
            $this->manager->persist($game);
        }
        $game->setDate ($date);
        $game->setTime ($time);
        $game->setField($field);
       
      //echo sprintf("%d %s %s %s %s %s %s\n",$seq,$num,$date,$time,$field->getDesc(),$homeTeam->getDesc(),$awayTeam->getDesc());
               
    }
    protected function addTeamToGame($game,$div,$type,$poolTeam,$schTeamKey = null)
    {
        if ($poolTeam) $gameTeam = $poolTeam;
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
    /* =================================================================
     * One sheet at a time
     */
    protected function processSheet($reader,$sheetName)
    {
        $sheet = $reader->getSheetByName($sheetName);
        if (!$sheet) return;
        $rows = $sheet->toArray();
        
        // Loop once for team
        foreach($rows as $row)
        {
            $this->processTeamRowLeft($row);
        }
        foreach($rows as $row)
        {
            $this->processTeamRowRight($row);
        }
        foreach($rows as $row)
        {
            $this->processGameRowLeft($sheetName,$row);
        }
        foreach($rows as $row)
        {
            $this->processGameRowRight($sheetName,$row);
        }
    }    
    /* =================================================================
     * Merge this back into the base class after some testing
     */
    public function process($params = array())
    {
        
        // For tracking changes
        $this->getEntityManager()->getEventManager()->addEventListener(
            array(Events::postUpdate, Events::postRemove, Events::postPersist),
            $this);

        // Need project
        $projectId = $this->projectId;
        
        if ($projectId)
        {
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
        
        // Type of team
        if (isset($params['type'])) $this->teamType = $params['type'];
        else                        $this->teamType = 'regular';
        
        $this->orgs      = array();
        $this->fields    = array();
        $this->phyTeams  = array();
        $this->poolTeams = array();
        $this->gameTeams = array();
        
        $reader = $this->excel->load($inputFileName);

        $sheets = array('10B','10G','12B','12G','14B','14G','16B','16G','19B','19G');
      //$sheets = array('10B');
        foreach($sheets as $sheet)
        {
            $this->processSheet($reader,$sheet);
        }

        // Finish up
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear(); // Need for multiple files

        return $this->getResults();
    }
 }
?>

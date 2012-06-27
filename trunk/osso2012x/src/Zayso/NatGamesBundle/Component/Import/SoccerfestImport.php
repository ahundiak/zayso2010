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

class SoccerfestImport extends ExcelBaseImport
{
    protected $clientFileName = 'NA';
    
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
    protected function processGameRow($sheetName,$row)
    {
        $num = (int)trim($row[0]);

        $date  = trim($row[1]);
        $time  = trim($row[3]);
        
        $field = trim($row[4]);
        $pool  = trim($row[5]);
        
        $home  = trim($row[6]);
        $away  = trim($row[7]);
        
        $div = substr($pool,0,4);

        $this->processGame($div,$num,$date,$time,$field,$home,$away);
        
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
    protected function processGame($div,$num,$date,$time,$fieldKey,$homeTeamKey,$awayTeamKey)
    {
        if ($num < 10) return;
        
        if (strpos($homeTeamKey,'VAC') !== FALSE) return;
        if (strpos($awayTeamKey,'VAC') !== FALSE) return;
        
        // Need to sleep on bye
        if (strpos($homeTeamKey,'BYE') !== false) return;
        if (strpos($awayTeamKey,'BYE') !== false) return;
        
        // Date time
        //$date = $this->processDate($date);
        $time = $this->processTime($time);
        
        //echo sprintf("%d %s %s %s %s %s\n",$num,$date,$time,$fieldKey,$homeTeamKey,$awayTeamKey);
        //die('game');
       
        // Field
        $field = $this->processField($fieldKey);
        if (!$field)
        {
            die('No field for ' . $seq);
        }
        //$homeTeamKey = $this->processNewPoolTeam($div,$homeTeamKey);
        //$awayTeamKey = $this->processNewPoolTeam($div,$awayTeamKey);
        
        // Do the games
        $game = $this->manager->loadEventForNum($this->projectId,$num);
        
        if (!$game)
        {
            die('No game for ' . $num);
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
        //$game->setDate ($date);
        if ($game->getTime() != $time)
        {
            echo sprintf("TIME  %03d %s %s %s %s %s\n",$num,$date,$time,$field->getDesc(),$homeTeamKey,$awayTeamKey);
            $game->setTime ($time);
        }
        if ($game->getField()->getId() != $field->getId())
        {
            echo sprintf("FIELD %03d %s %s %s %s %s\n",$num,$date,$time,$field->getDesc(),$homeTeamKey,$awayTeamKey);
            $game->setField($field);
        }
        
      //echo sprintf("%d %s %s %s %s %s %s\n",$seq,$num,$date,$time,$field->getDesc(),$homeTeam->getDesc(),$awayTeam->getDesc());
               
    }
    protected function addTeamToGame($game,$div,$type,$gameTeam)
    {
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
     * Basically a hard coded schedule
     */
    protected function processSoccerfestTeam($div,$key)
    {
        if (isset($this->gameTeams[$key])) return $this->gameTeams[$key];
        
        // Create
        $team = $this->newTeam($div, 'soccerfest', $key);
        $team->setLevel('regular');
        $team->setDesc1($key);
         
        $this->manager->persist($team);
        
        $this->gameTeams[$key] = $team;
        
        return $team;
    }
    protected function processMatch($age,$time,$fieldKey,$match)
    {
        if ($age  >  'U14') 
        {
            // Ignore D slots
            if (strpos($match,'D') !== false) return;
        }
      //if ($time != '0800') return;
        
        $this->num++;
        if (isset($this->games[$this->num])) return;
         
        $div = $age . substr($match,0,1);
        $home = substr($match,2,2);
        $away = substr($match,7,2);
        
        $field = $this->processField($fieldKey);
        $date = '20120704';
        
        $pool = $div . ' Soccerfest';
        $homeTeamKey = $home . ' Sf ' . $div;
        $awayTeamKey = $away . ' Sf ' . $div;
        
        $homeTeam = $this->processSoccerfestTeam($div,$homeTeamKey);
        $awayTeam = $this->processSoccerfestTeam($div,$awayTeamKey);
        
        $game = new Game();
        $game->setProject($this->project);
        $game->setNum ($this->num);
        $game->setPool($pool);
        $game->setDate($date);
        $game->setTime($time);

        $game->setField($field);
        
        $this->addTeamToGame($game,$div,'Home',$homeTeam);
        $this->addTeamToGame($game,$div,'Away',$awayTeam);
   
        $this->addReferee($game,GameReferee::TypeCR);
        $this->addReferee($game,GameReferee::TypeAR1);
        $this->addReferee($game,GameReferee::TypeAR2);
            
        $this->manager->persist($game);
        
      //echo sprintf("Match %s  %s %s %s\n",$age,$time,$fieldKey,$match);
      //echo sprintf("Match %s %s %s %s %s\n",$div,$time,$field->getDesc(),$homeTeam->getDesc(),$awayTeam->getDesc());
           
    }
    protected function processSchedule()
    {
        $this->num = 1000;
        
        $ages = array(
            'U10' => array('CELL1','CELL2','CELL3','CELL4','CELL5','CELL6'),
            'U12' => array('BC1',  'BC2',  'BC8',  'BC9',  'BC5',  'BC6'),
            'U14' => array('ASH1', 'ASH2', 'ASH3', 'ASH4', 'ASH5', 'ASH6'),
            'U16' => array('TAR1', 'TAR2', 'TAR3', 'TAR4', 'TAR5', 'TAR6'),
            'U19' => array('SCH1', 'SCH2', 'SCH3', 'RR4',  'RR5',  'RR6'),
        );
        $slots = array(
            '0800' => array('G A1 v B1','G A2 v B2','G A3 v B3','G A4 v B4','G A5 v B5','G A6 v B6'),
            '0900' => array('B A1 v B1','B A2 v B2','B A3 v B3','B A4 v B4','B A5 v B5','B A6 v B6'),
            '1000' => array('G C1 v D1','G C2 v D2','G C3 v D3','G C4 v D4','G C5 v D5','G C6 v D6'),
            '1100' => array('B C1 v D1','B C2 v D2','B C3 v D3','B C4 v D4','B C5 v D5','B C6 v D6'),
						
            '1200' => array('G B1 v A2','G B2 v A1','G B3 v A4','G B4 v A3','G B5 v A6','G B6 v A5'),
            '1300' => array('B B1 v A2','B B2 v A1','B B3 v A4','B B4 v A3','B B5 v A6','B B6 v A5'),
            '1400' => array('G D1 V C2','G D2 v C1','G D3 v C4','G D4 v C3','G D5 v C6','G D6 v C5'),
            '1500' => array('B D1 V C2','B D2 v C1','B D3 v C4','B D4 v C3','B D5 v C6','B D6 v C5'),
        );
        $agesGroupC = array(
            'U16' => array('TAR1', 'TAR2', 'TAR3'),
            'U19' => array('RR4',  'RR5',  'RR6'),
        );
        $slotsGroupC = array(
            '1000' => array('G C1 v C2','G C3 v C4','G C5 v C6'),
            '1100' => array('B C1 v C2','B C3 v C4','B C5 v C6'),
						
            '1400' => array('G C2 V C3','G C4 v C5','G C6 v C1'),
            '1500' => array('B C2 V C3','B C4 v C5','B C6 v C1'),
        );
        
        foreach($ages as $age => $fields)
        {
            foreach($slots as $time => $matches)
            {
                $i = 0;
                foreach($fields as $field)
                {
                    $match = $matches[$i++];
                    $this->processMatch($age,$time,$field,$match);
                }
            }
        }
        // Repeat for goupr C
        foreach($agesGroupC as $age => $fields)
        {
            foreach($slotsGroupC as $time => $matches)
            {
                $i = 0;
                foreach($fields as $field)
                {
                    $match = $matches[$i++];
                    $this->processMatch($age,$time,$field,$match);
                }
            }
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
        $this->teamType = 'soccerfest';
        
        $this->orgs      = array();
        $this->games     = array();
        $this->fields    = array();
        $this->phyTeams  = array();
        $this->poolTeams = array();
        $this->gameTeams = array();
        
        $this->processSchedule();
        
        // Finish up
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear(); // Need for multiple files

        return $this->getResults();
    }
 }
?>

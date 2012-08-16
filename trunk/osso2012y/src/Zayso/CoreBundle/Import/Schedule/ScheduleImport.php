<?php
namespace Zayso\CoreBundle\Import\Schedule;

use Zayso\CoreBundle\Import\BaseImport;

class ScheduleImport extends BaseImport
{
    protected $record = array
    (
      'projectId' => array('cols' => 'PID',    'req' => false, 'default' => 0),
      'gameNum'   => array('cols' => 'Num',    'req' => true,  'default' => 0),
      'date'      => array('cols' => 'Date',   'req' => true,  'default' => null),
      'time'      => array('cols' => 'Time',   'req' => true,  'default' => null),
      'field'     => array('cols' => 'Field',  'req' => true,  'default' => null),
        
      'homeTeam'  => array('cols' => array('Home','Home Team'), 'req' => true,  'default' => null),
      'awayTeam'  => array('cols' => array('Away','Away Team'), 'req' => true,  'default' => null),
        
      'homeRegion'  => array('cols' => 'Home Region', 'req' => false,  'default' => null),
      'awayRegion'  => array('cols' => 'Away Region', 'req' => false,  'default' => null),
      'homeDiv'     => array('cols' => 'Home Div',    'req' => false,  'default' => null),
      'awayDiv'     => array('cols' => 'Away Div',    'req' => false,  'default' => null),
        
      'crew'      => array('cols' => 'Crew',   'req' => false, 'default' => null),
    );
    protected function getSheetNames()
    {
        return array('ZAYSO');
    }
    protected $fields = array();
    
    protected function getField($projectId,$key)
    {
        if (isset($this->fields[$key])) return $this->fields[$key];
        
        $manager = $this->manager;
        
        $field = $manager->loadProjectFieldForKey($projectId,$key);
        if (!$field)
        {
            $field = $manager->newProjectField($projectId);
            $field->setKey($key);
            $manager->persist($field);
        }
        $this->fields[$key] = $field;
        return $field;
    }
    protected $teams = array();
    
    protected function getTeam($projectId,$key,$region1,$region2,$div1,$div2)
    {
        if (!$key) return null;

        if (isset($this->teams[$key])) return $this->teams[$key];

        $manager = $this->manager;
        $team = $manager->loadTeamForKey($projectId,$key);
        if ($team)
        {
            $this->teams[$key] = $team;
            return $team;
        }
            
        // Process region
        $region = null;
        $age = null;
        $gender = null;
        
        if ($key[0] == 'R')
        {
            $region = substr($key,0,5);
            $age    = substr($key,6,3);
            $gender = substr($key,9,1);
        }
        if (!$region)
        {
            if ($region1) $region = $region1;
            else          $region = $region2;
            if (!$region) return null;
            
            if ($div1) $div = $div1;
            else       $div = $div2;
            
            if (!$div) return null;
            
            $age    = substr($div,0,3);
            $gender = substr($div,3,1);
        }
        if ($age == 'VIP') $gender = 'C';
        
        $team = $manager->newTeam($projectId);
        $team->setTeamKey($key);
        $team->setTypePhysical();
        $team->setSourceImport();
        $team->setLevelRegular();
        
        $regionId = 'AYSO' . $region;
        $org = $manager->getRegionReference($regionId);
        $team->setOrg($org);
        
        $team->setAge   ($age);
        $team->setGender($gender);
                
        $manager->persist($team);
        
        $this->teams[$key] = $team;
        return $team;
    }
    protected function processItem($item)
    {
        $manager = $this->manager;
        
        $gameNum = (int)$item->gameNum;
        if (!$gameNum) return;
        
      //$gameNum += 1000;
        
        $projectId = $item->projectId;
        if (!$projectId) $projectId = $this->projectId;
        
      //$project = $manager->getProjectReference($projectId);
        
         // Used later
        $homeTeam = $this->getTeam($projectId,$item->homeTeam,$item->homeRegion,$item->awayRegion,$item->homeDiv,$item->awayDiv);
        $awayTeam = $this->getTeam($projectId,$item->awayTeam,$item->awayRegion,$item->homeRegion,$item->awayDiv,$item->homeDiv);
        
        if (!$homeTeam || !$awayTeam) die(sprintf("Missing team for %u %s %s\n",$gameNum,$item->homeTeam,$item->awayTeam));
       
        $this->total++;
        
        $game = $manager->loadEventForProjectNum($projectId,$gameNum);
        if (!$game)
        {
            // New Game
            $game = $manager->newGameWithTeams($projectId);
            $game->setNum ($gameNum);
            
            $age = $homeTeam->getAge();

            // Add Referee crew? perhaps add crew to schedule
            $gamePerson = $manager->newGamePerson($projectId);
            $gamePerson->setTypeAsCR();
            $gamePerson->setProtected(true);
            $gamePerson->setEvent($game);
            $game->addPerson($gamePerson);
 
            switch($age)
            {
                case 'U05':
                case 'U06':
                case 'VIP':
                    //$eventPerson->setTypeAsREF();
                    break;
                
                default:
                    $gamePerson = $manager->newGamePerson($projectId);
                    $gamePerson->setTypeAsAR1();
                    $gamePerson->setProtected(true);
                    $gamePerson->setEvent($game);
                    $game->addPerson($gamePerson);
                    
                    $gamePerson = $manager->newGamePerson($projectId);
                    $gamePerson->setTypeAsAR2();
                    $gamePerson->setProtected(true);
                    $gamePerson->setEvent($game);
                    $game->addPerson($gamePerson);
            }
            // Persist It
            $manager->persist($game);
        }
        $game->setDate($this->processDate($item->date));
        $game->setTime($this->processTime($item->time));
        $game->setOrg($homeTeam->getOrg());
        
        // Check field
        $field = $this->getField($projectId,$item->field);
        $game->setField($field);
        
        // Update home/away links
        $game->getHomeTeam()->setTeam($homeTeam);
        $game->getAwayTeam()->setTeam($awayTeam);

        return;
        
        echo sprintf("Item %s %s %s %s\n",$item->gameNum,$item->time,$item->homeTeam,$item->awayTeam);
    }

}
?>

<?php

namespace Zayso\AreaBundle\Component\Import;

use Zayso\CoreBundle\Component\Import\BaseImport;
use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\EventPerson;

class GameScheduleImport extends BaseImport
{
    protected $record = array
    (
      'projectId' => array('cols' => 'PID',    'req' => true,  'default' => 0),
      'gameNum'   => array('cols' => 'Number', 'req' => true,  'default' => 0),
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
    public function __construct($gameManager)
    {
        parent::__construct($gameManager->getEntityManager());
        $this->gameManager = $gameManager;
    }
    protected $fields = array();
    
    protected function getField($projectId,$key)
    {
        if (isset($this->fields[$key])) return $this->fields[$key];
        
        $manager = $this->gameManager;
        
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

        $manager = $this->gameManager;
        $team = $manager->loadTeamForKey($projectId,$key);
        if ($team)
        {
            $this->teams[$key] = $team;
            return $team;
        }
        
        // If loaded from eayso
        /*
        $keyx = $key;
        $key = str_replace('-','',$key);
        $key = substr($key,0,11);
      //$key = substr($key,0,8) . 'C' . substr($key,8,2);
        $team = $this->gameManager->loadTeamForKey($projectId,$key);
        if ($team)
        {
            $this->teams[$keyx] = $team;
            return $team;
        }*/
            
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
        $team = $manager->newTeam($projectId);
        $team->setTeamKey($key);
        $team->setSource('schedule_import');
        
        $regionId = 'AYSO' . $region;
        $org = $manager->getRegionReference($regionId);
        $team->setOrg($org);
        
        $team->setAge   ($age);
        $team->setGender($gender);
                
        $manager->persist($team);
        
        $this->teams[$key] = $team;
        return $team;
    }
    
    public function processItem($item)
    {
        if (!$item->projectId) return;
        if (!$item->gameNum)   return;
        
        $projectId = $item->projectId;
        
        // Used later
        $homeTeam = $this->getTeam($projectId,$item->homeTeam,$item->homeRegion,$item->awayRegion,$item->homeDiv,$item->awayDiv);
        $awayTeam = $this->getTeam($projectId,$item->awayTeam,$item->awayRegion,$item->homeRegion,$item->awayDiv,$item->homeDiv);

        if (!$homeTeam || !$awayTeam) return;
        
        // Create a game if needed
        $this->total++;
        $manager = $this->gameManager;
        
        $game = $manager->loadEventForProjectNum($projectId,$item->gameNum);
        if (!$game)
        {
            // New Game
            $game = $manager->newGameWithTeams($projectId);
            $game->setNum ($item->gameNum);
            
            $age = $homeTeam->getAge();

            // Add Referee crew? perhaps add crew to schedule
            $eventPerson = new EventPerson();
            $eventPerson->setTypeAsCR();
            $eventPerson->setProtected(true);
            $eventPerson->setEvent($game);
            $game->addPerson($eventPerson);

            switch($age)
            {
                case 'U05':
                case 'U06':
                    //$eventPerson->setTypeAsREF();
                    break;
                
                default:
                    $eventPerson = new EventPerson();
                    $eventPerson->setTypeAsAR1();
                    $eventPerson->setProtected(true);
                    $eventPerson->setEvent($game);
                    $game->addPerson($eventPerson);
                    
                    $eventPerson = new EventPerson();
                    $eventPerson->setTypeAsAR2();
                    $eventPerson->setProtected(true);
                    $eventPerson->setEvent($game);
                    $game->addPerson($eventPerson);
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
    }
}
?>

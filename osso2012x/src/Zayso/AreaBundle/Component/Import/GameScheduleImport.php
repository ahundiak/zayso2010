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
      'homeTeam'  => array('cols' => 'Home',   'req' => true,  'default' => null),
      'awayTeam'  => array('cols' => 'Away',   'req' => true,  'default' => null),
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
        
        $field = $this->gameManager->loadProjectFieldForKey($projectId,$key);
        if (!$field)
        {
            $field = $this->gameManager->newProjectField($projectId);
            $field->setKey($key);
            $this->getEntityManager()->persist($field);
        }
        $this->fields[$key] = $field;
        return $field;
    }
    protected $teams = array();
    
    protected function getTeam($projectId,$key)
    {
        if (isset($this->teams[$key])) return $this->teams[$key];
        
        $team = $this->gameManager->loadTeamForKey($projectId,$key);
        if ($team)
        {
            $this->teams[$key] = $team;
            return $team;
        }
        $team = $this->gameManager->newTeam($projectId);
        $team->setTeamKey($key);
        $team->setSource('schedule_import');
        
        $regionId = 'AYSO' . substr($key,0,5);
        $org = $this->gameManager->getRegionReference($regionId);
        $team->setOrg($org);
        
        $team->setAge   (substr($key,5,3));
        $team->setGender(substr($key,8,1));
                
        $this->getEntityManager()->persist($team);
        
        $this->teams[$key] = $team;
        return $team;
    }
    
    public function processItem($item)
    {
        if (!$item->projectId) return;
        if (!$item->gameNum)   return;
        
        $this->total++;
        
        $gameManager = $this->gameManager;
        $em = $this->getEntityManager();
        
        $projectId = $item->projectId;
        
        // Used later
        $homeTeam = $this->getTeam($projectId,$item->homeTeam);
        $awayTeam = $this->getTeam($projectId,$item->awayTeam);

        // Create a game if needed
        $game = $gameManager->loadEventForProjectNum($projectId,$item->gameNum);
        if (!$game)
        {
            // New Game
            $game = $gameManager->newGameWithTeams($projectId);
            $game->setNum ($item->gameNum);

            // Add Referee crew? perhaps add crew to schedule
            $eventPerson = new EventPerson();
            $eventPerson->setTypeAsCR();
            $eventPerson->setProtected(true);
            $eventPerson->setEvent($game);
            $game->addPerson($eventPerson);

            $age = $homeTeam->getAge();
            if ($age > 'U06')
            {
                $eventPerson = new EventPerson();
                $eventPerson->setTypeAsCR2();
                $eventPerson->setProtected(true);
                $eventPerson->setEvent($game);
                $game->addPerson($eventPerson);
            }
            // Persist It
            $em->persist($game);
        }
        else
        {
            $age = $homeTeam->getAge();
            if ($age == 'U08')
            {
                $cr2 = $game->getPersonForType(EventPerson::TypeCR2);
                if (!$cr2)
                {
                    $eventPerson = new EventPerson();
                    $eventPerson->setTypeAsCR2();
                    $eventPerson->setProtected(true);
                    $eventPerson->setEvent($game);
                    $em->persist($eventPerson);
                }
            }
        }
        $game->setDate($item->date);
        $game->setTime($item->time);
        $game->setOrg($homeTeam->getOrg());
        
        // Check field
        $field = $this->getField($projectId,$item->field);
        if ($game->getField() && $game->getField()->getId() == $field->getId()) {}
        else  
        {
            $game->setField($field);
        }
        
        // Update home/away links
        $game->getHomeTeam()->setTeam($homeTeam);
        $game->getAwayTeam()->setTeam($awayTeam);

        return;
    }
}
?>

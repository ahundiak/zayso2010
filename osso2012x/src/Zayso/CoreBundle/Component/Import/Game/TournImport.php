<?php

namespace Zayso\CoreBundle\Component\Import\Game;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event        as Game;
use Zayso\CoreBundle\Entity\EventTeam    as GameTeam;
use Zayso\CoreBundle\Entity\ProjectField as Field;

class TournImport extends ExcelBaseImport
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
        parent::__construct($manager->getEntityManager());
    }
    protected $record = array(
      'num'      => array('cols' => 'Game',  'req' => true,  'default' => 0),
      'div'      => array('cols' => 'Div',   'req' => true,  'default' => 0),
      'dow'      => array('cols' => 'DOW',   'req' => true,  'default' => 0),
      'date'     => array('cols' => 'Date',  'req' => true,  'default' => 0),
      'time'     => array('cols' => 'Time',  'req' => true,  'default' => 0),
      'field'    => array('cols' => 'Field', 'req' => true,  'default' => 0),
      'pool'     => array('cols' => 'Pool',  'req' => true,  'default' => 0),
        
      'homeSchTeam' => array('cols' => 'Home Sch Team','req' => true,  'default' => 0),
      'awaySchTeam' => array('cols' => 'Away Sch Team','req' => true,  'default' => 0),
      'homePhyTeam' => array('cols' => 'Home Phy Team','req' => true,  'default' => 0),
      'awayPhyTeam' => array('cols' => 'Away Phy Team','req' => true,  'default' => 0),
    );
    public function processSchTeam($key)
    {
        if (!$key) return null;
        $manager = $this->manager;
        
        $team = $manager->loadSchTeamForKey($this->projectId,$key);
        
        if ($team) return $team;
        
        $parts  = explode(' ',$key);
        $div    = trim($parts[0]);
        
        $team = new Team();
        $team->setProject($this->project);
        $team->setType('schedule');
        $team->setStatus('Active');
        $team->setSource('import');
        $team->setTeamKey($key);
        $team->setAge   (substr($div,0,3));
        $team->setGender(substr($div,3,1));
        
        $manager->persist($team);
        
        return $team;
    }
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
        
       
        $team = new Team();
        $team->setProject($this->project);
        $team->setType('physical');
        $team->setStatus('Active');
        $team->setSource('import');
        $team->setTeamKey($key);
        $team->setOrg($org);
        $team->setAge   (substr($div,0,3));
        $team->setGender(substr($div,3,1));
        
        $manager->persist($team);
        
        return $team;
    }
    public function processGame($item,$field,$homeSchTeam,$awaySchTeam)
    {
        $manager = $this->manager;
        
        // Filter on numbers
        $num = (int)$item->num;
        if (!$num) return null;
        
        $game = $manager->loadEventForNum($this->projectId,$num);
        if (!$game)
        {
            $game = new Game();
            $game->setProject($this->project);
            $game->setNum($num);
            
            $homeGameTeam = new GameTeam();
            $homeGameTeam->setEvent($game);
            $homeGameTeam->setType('Home');
            
            $awayGameTeam = new GameTeam();
            $awayGameTeam->setEvent($game);
            $awayGameTeam->setType('Away');
            
            $game->addTeam($homeGameTeam);
            $game->addTeam($awayGameTeam);
            
            $manager->persist($game);
        }
        $game->setDate ($item->date);
        $game->setTime ($item->time);
        $game->setPool ($item->pool);
        $game->setField($field);
        
        $game->getHomeTeam()->setTeam($homeSchTeam);
        $game->getAwayTeam()->setTeam($awaySchTeam);
        
        return $game;
    }
    public function processField($key)
    {
        $manager = $this->manager;
        $field = $manager->loadFieldForKey($this->projectId,$key);
        if ($field) return $field;
        
        $field = new Field();
        $field->setProject($this->project);
        $field->setKey($key);
        
        $manager->persist($field);
        
        return $field;
        
    }
    public function processItem($item)
    {
        $manager = $this->manager;
        
        $homePhyTeam = $this->processPhyTeam($item->homePhyTeam);
        $awayPhyTeam = $this->processPhyTeam($item->awayPhyTeam);
        
        $homeSchTeam = $this->processSchTeam($item->homeSchTeam);
        $awaySchTeam = $this->processSchTeam($item->awaySchTeam);
        
        if ($homePhyTeam) $homeSchTeam->setParent($homePhyTeam);
        if ($awayPhyTeam) $awaySchTeam->setParent($awayPhyTeam);
        
        $field = $this->processField($item->field);
        
        $item->time = str_replace(':','',$item->time);
        
        $game = $this->processGame($item,$field,$homeSchTeam,$awaySchTeam);
        
        $manager->flush();
        
        //Debug::dump($item);
        //die('Item processed' . "\n");
    }
    
}
?>

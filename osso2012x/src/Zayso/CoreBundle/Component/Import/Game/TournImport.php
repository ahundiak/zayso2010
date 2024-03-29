<?php

namespace Zayso\CoreBundle\Component\Import\Game;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event        as Game;
use Zayso\CoreBundle\Entity\EventTeam    as GameTeamRel;
use Zayso\CoreBundle\Entity\EventPerson  as GameReferee;
use Zayso\CoreBundle\Entity\ProjectField as Field;

class TournImport extends ExcelBaseImport
{
    protected $manager = null;
    protected $teams = array();
    
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
        
        if (isset($this->teams[$key])) return $this->teams[$key];
        
        $manager = $this->manager;
        
        $team = $manager->loadPoolTeamForKey($this->projectId,$key);
        
        if ($team) return $team;
        
        $parts  = explode(' ',$key);
        $div    = trim($parts[0]);
        
        $team = $this->newTeam($div,'pool',$key);
        $team->setDesc($key);
        
        $manager->persist($team);
        
        $this->teams[$key] = $team;
 
        return $team;
    }
    public function processPhyTeam($key)
    {
        if (!$key) return null;
        $manager = $this->manager;
        
        if (isset($this->teams[$key])) return $this->teams[$key];
        
        $team = $manager->loadPhyTeamForKey($this->projectId,$key);
        
        if ($team) return $team;
        
        $prefix = explode(' ',$key);
        $parts  = explode('-',$prefix[0]);
        $region = trim($parts[0]);
        $div    = trim($parts[1]);
        
        $org = $manager->getRegionReference('AYSO' . $region);
        
        $team = $this->newTeam($div,'physical',$key,$org);
        $team->setDesc($key);
        
        $manager->persist($team);
        
        $this->teams[$key] = $team;
        
        return $team;
    }
    protected function addTeamToGame($game,$div,$type,$poolTeam,$schTeamKey)
    {
        if ($poolTeam) 
        {
            $gameTeam = $poolTeam;
        }
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
    public function processGame($item,$field,$homePoolTeam,$awayPoolTeam)
    {
        $manager = $this->manager;
        
        // Filter on numbers
        $num = (int)$item->num;
        if (!$num) return null;
        
        $div = substr($item->pool,0,4);
        
        $game = $manager->loadEventForNum($this->projectId,$num);
        
        if (!$game)
        {
            $game = new Game();
            $game->setProject($this->project);
            $game->setNum ($num);
            $game->setPool($item->pool);

            $this->addTeamToGame($game,$div,'Home',$homePoolTeam,$item->homeSchTeam);
            $this->addTeamToGame($game,$div,'Away',$awayPoolTeam,$item->awaySchTeam);
   
            $this->addReferee($game,GameReferee::TypeCR);
            $this->addReferee($game,GameReferee::TypeAR1);
            $this->addReferee($game,GameReferee::TypeAR2);
            
            $manager->persist($game);
        }
        $game->setDate ($item->date);
        $game->setTime ($item->time);
      //$game->setPool ($item->pool);
        $game->setField($field);
        
        //$game->getHomeTeam()->setTeam($homePoolTeam);
        //$game->getAwayTeam()->setTeam($awayPoolTeam);
        
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
      //Debug::dump($item); die('processItem');
        $manager = $this->manager;
        
        $homePhyTeam = $this->processPhyTeam($item->homePhyTeam);
        $awayPhyTeam = $this->processPhyTeam($item->awayPhyTeam);
        
        $homePoolTeam = $this->processPoolTeam($item->homeSchTeam);
        $awayPoolTeam = $this->processPoolTeam($item->awaySchTeam);
        
        $this->setPhyTeamForPoolTeam($homePoolTeam,$homePhyTeam);
        $this->setPhyTeamForPoolTeam($awayPoolTeam,$awayPhyTeam);
        
        $field = $this->processField($item->field);
        
        $item->time = str_replace(':','',$item->time);
        
        $this->processGame($item,$field,$homePoolTeam,$awayPoolTeam);
        
        $manager->flush();
        return;
        
        Debug::dump($item);
        die('Item processed' . "\n");
    }
    protected function setPhyTeamForPoolTeam($poolTeam,$phyTeam)
    {
        if (!$poolTeam || !$phyTeam) return;
        
        $poolTeam->setParent($phyTeam);
        $poolTeam->setOrg   ($phyTeam->getOrg());
        
        $phyTeamKey  = $phyTeam->getKey();  // R0894-U14G Coach
        $poolTeamKey = $poolTeam->getKey(); // U14G PP A1
        
        $parts = explode(' ',$poolTeamKey);
        if (!isset ($parts[2])) return;
        $num = trim($parts[2]);
        
        $coach  = substr($phyTeamKey,11);
        $region = substr($phyTeamKey,0,5);
        
        $desc = sprintf('%s %s %s',$num,$region,$coach);
        
        $poolTeam->setDesc($desc);
    }
}
?>

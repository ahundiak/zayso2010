<?php

namespace Zayso\CoreBundle\Component\Import\Game;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event        as Game;
use Zayso\CoreBundle\Entity\EventTeam    as GameTeamRel;
use Zayso\CoreBundle\Entity\EventPerson  as GameReferee;
use Zayso\CoreBundle\Entity\ProjectField as Field;

class TeamsImport extends ExcelBaseImport
{
    protected $manager = null;
    
    public function __construct($manager)
    {
        $this->manager = $manager;
        parent::__construct($manager->getEntityManager());
    }
    protected $record = array(
      'div'  => array('cols' => 'Div',   'req' => true,  'default' => 0),
      'key'  => array('cols' => 'Key',   'req' => true,  'default' => 0),
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
    public function processPhyTeam($div,$key)
    {
        if (!$div) return null;
        if (!$key) return null;
        
        $dash = strrpos($key,'-');
        if ($dash === false) die('No dash in ' . $key . "\n");
        
        $coach = substr($key,0,$dash);
        $region = (int)substr($key,$dash+1);
        
        $key1 = sprintf('R%04u-%s %s',$region,$div,$coach);
        $key2 = sprintf('%s %s',$div,$key);
        
        $manager = $this->manager;
        
        $team = $manager->loadPhyTeamForKey($this->projectId,$key1);
        if ($team) return $team;
        
        $org = $manager->getRegionReference('AYSO' . substr($key1,0,5));
        
        $team = $this->newTeam($div,'physical',$key1,$org);
        $team->setKey2($key2);
        
        $manager->persist($team);
        
        return $team;
    }
    public function processItem($item)
    {
      //Debug::dump($item); die('processItem');
        $manager = $this->manager;
        
        $div = $item->div;
        $key = $item->key;
        
        if (!$div || !$key) return;
        
        $phyTeam = $this->processPhyTeam($div,$key);
        
        $manager->flush();
        
        return;
        
        $homePhyTeam = $this->processPhyTeam($item->homePhyTeam);
        $awayPhyTeam = $this->processPhyTeam($item->awayPhyTeam);
        
        $homePoolTeam = $this->processPoolTeam($item->homeSchTeam);
        $awayPoolTeam = $this->processPoolTeam($item->awaySchTeam);
        
        if ($homePoolTeam) $homePoolTeam->setParent($homePhyTeam);
        if ($awayPoolTeam) $awayPoolTeam->setParent($awayPhyTeam);
        
        $field = $this->processField($item->field);
        
        $item->time = str_replace(':','',$item->time);
        
        $game = $this->processGame($item,$field,$homePoolTeam,$awayPoolTeam);
        
        $manager->flush();
        return;
        
        Debug::dump($item);
        die('Item processed' . "\n");
    }
    
}
?>

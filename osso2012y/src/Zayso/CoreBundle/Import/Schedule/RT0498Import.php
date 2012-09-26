<?php
namespace Zayso\CoreBundle\Import\Schedule;

use Zayso\CoreBundle\Import\BaseImport;

class RT0498Import extends BaseImport
{
    protected $record = array
    (
      'projectId' => array('cols' => 'PID',    'req' => false, 'default' => 0),
      'gameNum'   => array('cols' => array('Num','Game #'),    'req' => true,  'default' => 0),
      'date'      => array('cols' => 'Date',   'req' => true,  'default' => null),
      'time'      => array('cols' => 'Time',   'req' => true,  'default' => null),
      'field'     => array('cols' => 'Field',  'req' => true,  'default' => null),
        
      'homeTeam'  => array('cols' => array('Home','Home Team'),           'req' => true,  'default' => null),
      'awayTeam'  => array('cols' => array('Away','Away Team','Visitor'), 'req' => true,  'default' => null),
        
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
        $region = 'R0498';
        $age    = substr($key,0,3);
        $gender = substr($key,3,1);
        
        $team = $manager->newTeam($projectId);
        $team->setTeamKey($key);
        $team->setTypePlayoff();
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
        
        $projectId = 82;
        
        if ($gameNum < 0)
        {
            $gameNum = -1 * $gameNum;
            $game = $manager->loadEventForProjectNum($projectId,$gameNum);
            if ($game) 
            {
                $this->total++;
                $manager->remove($game);
            }
            return;
        }
        
         // Used later
        $homeTeam = $this->getTeam($projectId,$item->homeTeam,$item->homeRegion,$item->awayRegion,$item->homeDiv,$item->awayDiv);
        $awayTeam = $this->getTeam($projectId,$item->awayTeam,$item->awayRegion,$item->homeRegion,$item->awayDiv,$item->homeDiv);
        
        if (!$homeTeam || !$awayTeam) return; // die(sprintf("Missing team for %u %s %s\n",$gameNum,$item->homeTeam,$item->awayTeam));
       
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
        $field = $this->getField(80,$item->field);
        $game->setField($field);
        
        // Update home/away links
        $game->getHomeTeam()->setTeam($homeTeam);
        $game->getAwayTeam()->setTeam($awayTeam);

        $pool = $homeTeam->getKey();
        if (substr($pool,5,2) == 'PP') $pool = substr($pool,0,9);
        else
        {
            $div = substr($pool,0,5);
            switch(substr($pool,5))
            {
                case 'Winner A':
                case '1st Points A':
                    $pool = $div . 'SF1';
                    break;
                
                case 'Winner B':
                case '1st Points B':
                    $pool = $div . 'SF2';
                    break;
                
                case 'RU Semi1':
                    $pool = $div . 'CM';
                    break;
                
                case 'Winner Semi1':
                    $pool = $div . 'FM';
                    break;
                 
               default:
                    die('Pool ' . $pool . "\n");
            }
        }
        $game->setPool($pool);
        // return;
        
        echo sprintf("Item %s %s %s %s %s %s %s\n",
                $game->getNum(),$game->getDate(),$game->getTime(),$game->getFieldDesc(),$game->getPool(),$item->homeTeam,$item->awayTeam);
        //die();
    }
    protected function processPool($row)
    {
        $pool = trim($row[0]);
        if (!$pool) return;
        $pool = substr($pool,0,4) . ' PP ' . substr($pool,5,2);
        
        $phyTeamKeyx = 'R0498-' . trim($row[1]);
        
        $manager = $this->manager;
        $phyTeam = $manager->loadTeamForLikeKey(80,$phyTeamKeyx);
        if (!$phyTeam)
        {
            echo "*** Missing team $phyTeamKeyx\n";
            return;
        }
        $phyTeamKey = $phyTeam->getKey();
        
        $poolTeam = $manager->loadTeamForKey(82,$pool);
        if ($poolTeam)
        {
            $this->teams[$phyTeamKey] = $poolTeam;
            return;
        }
        echo 'NewPoolTeam ' . $pool . ' ' . $phyTeamKey . "\n";
        
        $poolTeam = $manager->newTeam(82);
        $poolTeam->setTeamKey($pool);
        $poolTeam->setTypePool();
        $poolTeam->setSourceImport();
        $poolTeam->setLevelRegular();
        $poolTeam->setParent($phyTeam);
        
        $regionId = 'AYSOR0498';
        $org = $manager->getRegionReference($regionId);
        $poolTeam->setOrg($org);
        
        $poolTeam->setAge   (substr($pool,0,3));
        $poolTeam->setGender(substr($pool,3,1));
                
        $desc = substr($pool,8,2) . ' ' . substr($phyTeamKey,0,5) . ' ' . substr($phyTeamKey,14);
        $desc = substr($pool,8,2) . ' ' . substr($phyTeamKey,0);
        $poolTeam->setDesc1($desc);
        
        $manager->persist($poolTeam);
        
        $this->teams[$phyTeamKey] = $poolTeam;
        
    }
    protected function processPools($rows)
    {
        $this->teams = array();
        
        foreach($rows as $row)
        {
            $this->processPool($row);
        }
    }
    protected function processInputFile($inputFileName)
    {
        $reader = $this->excel->load($inputFileName);

        $ws = $reader->getSheetByName('2012 Brackets');
        $rows = $ws->toArray();
        
        $this->processPools($rows);
        
        $ws = $reader->getSheetByName('2012 Schedule (Div Sort)');
        $rows = $ws->toArray();
        
        $this->processHeaderRow(array_shift($rows));
        if (count($this->errors)) 
        {
            print_r($this->errors);
            return;
        }
        // Process the data
        foreach($rows as $row)
        {
            $item = $this->processDataRow($row);
            $this->processItem($item);
        } 
        return;       
    }

}
?>

<?php

namespace Zayso\NatGamesBundle\Component\Import;

use Zayso\ZaysoBundle\Entity\Game;
use Zayso\ZaysoBundle\Entity\GameTeam;

use Zayso\ZaysoBundle\Component\Debug;
use Zayso\ZaysoBundle\Component\Import\BaseImport;
use Zayso\ZaysoBundle\Component\DataTransformer\DateTransformer;
use Zayso\ZaysoBundle\Component\DataTransformer\TimeTransformer;

class Schedule2010Import extends BaseImport
{
    protected $dateTransformer;
    protected $timeTransformer;

    protected $fields = array();
    protected $gameNum = 1000;

    protected function init()
    {
        parent::init();
        $this->dateTransformer = new DateTransformer();
        $this->timeTransformer = new TimeTransformer();
    }
    protected function getTeamAge($team)
    {
        $age = substr($team,1,3);
        switch($age)
        {
            case 'U10': case 'U12': case 'U14': case 'U16': case 'U19':
                return $age;
        }
        return null;
    }
    protected function getTeamGender($team)
    {
        if ($team == 'BYE') return null;

        $gender = substr($team,1,1);
        switch($gender)
        {
            case 'B': case 'G':
                return $gender;
        }
        return null;
    }
    protected function getGameAge($home,$away)
    {
        $age = $this->getTeamAge($home);
        if ($age) return $age;
        return $this->getTeamAge($away);
    }
    protected function getGameGender($home,$away)
    {
        $gender = $this->getTeamGender($home);
        if ($gender) return $gender;
        return $this->getTeamGender($away);
    }
    protected function getGameType($home,$away)
    {
        if (($home == 'BYE') || ($away == 'BYE')) return 'BYE';
        if (strpos($home,'-')) return 'PP';
        if (strpos($home,'Q')) return 'QF';
        if (strpos($home,'C')) return 'CM';
        if (strpos($home,'S')) return 'SF';
        if (strpos($home,'F')) return 'F';

        return 'Unknown';
    }

    protected function addGame($date,$time,$field,$home,$away)
    {
        if (!$home) return;
        $this->total++;

        $game = new Game();
        $game->setNum(++$this->gameNum);
        $game->setProject($this->project);
        $game->setType  ($this->getGameType  ($home,$away));
        $game->setAge   ($this->getGameAge   ($home,$away));
        $game->setGender($this->getGameGender($home,$away));
        $game->setOrgKey('');
        $game->setStatus('Normal');

        $date = $this->dateTransformer->reverseTransform($date);
        $time = $this->timeTransformer->reverseTransform($time);
        
        $game->setDate($date);
        $game->setTime($time);
        $game->setFieldKey($field);

        echo sprintf("%s %s %s %s %s\n",$date,$time,$field,$home,$away);
    }
    protected function processRowField($row)
    {
        if (count($row) < 3) return false;
        if ($row[0]) return false;
        if ($row[1]) return false;
        $field = $row[2];
        if (!$field) return false;
        if (substr($field,0,5) != 'FIELD') return false;

        // if (count($this->fields)) die();

        $this->fields = array();
        foreach($row as $index => $value)
        {
            if (substr($value,0,5) == 'FIELD')
            {
                $this->fields[$index] = $value;
            }
        }
        // print_r($this->fields);
        // echo "\n";
        return true;
    }
    protected function processRowGame($row)
    {
        if (count($row) < 3) return false;
        $date = $row[0];
        $time = $row[1];
        if (!$date || !$time) return false;
        
        foreach($this->fields as $index => $field)
        {
            if (isset($row[$index]))
            {
                // Pull Teams
                $home = $row[$index];
                if (isset($row[$index+1])) $away = $row[$index+1];
                else                       $away = '';
                
                // The game
                $this->addGame($date,$time,$field,$home,$away);
                //echo sprintf("%s %s %s %s %s\n",$date,$time,$field,$home,$away);
            }
        }
        return true;
    }
    protected function processFile($fp)
    {
        while($row = fgetcsv($fp))
        {
            array_walk($row, function(&$value) {$value = trim($value);});
            $this->processRowField($row);
            $this->processRowGame ($row);
        }
    }
}
?>

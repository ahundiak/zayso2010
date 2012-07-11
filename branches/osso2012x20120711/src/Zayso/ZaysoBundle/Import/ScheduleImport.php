<?php
namespace Zayso\ZaysoBundle\Import;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\PhyTeam;
use Zayso\ZaysoBundle\Entity\PhyTeamPerson;
use Zayso\ZaysoBundle\Entity\SchTeam;

use Zayso\ZaysoBundle\Entity\Game;
use Zayso\ZaysoBundle\Entity\GameTeam;

class ScheduleImport extends BaseImport
{
    protected $record = array
    (
      'gameNum'  => array('cols' => 'Game',      'req' => false, 'default' => 0),
      'date'     => array('cols' => 'Date',      'req' => true,  'default' => ''),
      'time'     => array('cols' => 'Time',      'req' => true,  'default' => ''),
      'field'    => array('cols' => 'Field',     'req' => true,  'default' => ''),
      'homeTeam' => array('cols' => 'Home Team', 'req' => true,  'default' => ''),
      'awayTeam' => array('cols' => 'Away Team', 'req' => true,  'default' => ''),
    );
    protected function init()
    {
        parent::init();
        $em = $this->getEntityManager();
        $this->projectRepo = $em->getRepository('ZaysoBundle:Project');
        $this->phyTeamRepo = $em->getRepository('ZaysoBundle:PhyTeam');
        $this->schTeamRepo = $em->getRepository('ZaysoBundle:SchTeam');
        $this->gameRepo    = $em->getRepository('ZaysoBundle:Game');
    }
    protected function processDate($date)
    {
        if (!$date) return $date;

        $parts = explode('/',$date);
        if (count($parts) != 3) die('Bad date ' . $date);

        $year = (int)$parts[2];
        if ($year < 100)
        {
            if ($year < 50) $year += 2000;
            else            $year += 1900;
        }
        $date = sprintf('%04u%02u%02u',$year,$parts[0],$parts[1]);

        return $date;
    }
    protected function processTime($time)
    {
        if (!$time) return $time;

        if ($time == 'TBD') return $time;
        if ($time == 'BYE') return $time;

        $parts = explode(':',$time);
        if (count($parts) != 2) die('Bad time ' . $time);

        $time = sprintf('%02u%02u',$parts[0],$parts[1]);

        return $time;
    }
    public function processItem($item)
    {
        $em = $this->getEntityManager();

        if (!$item->date)     return;
        if (!$item->homeTeam) return;

        $this->total++;

        // Dates and times
        $item->date = $this->processDate($item->date);
        $item->time = $this->processTime($item->time);

        // Get rid of dashes
        $item->homeTeam = str_replace('-','',$item->homeTeam);
        $item->awayTeam = str_replace('-','',$item->awayTeam);

        // See if have existing game
        $gameNum = $item->gameNum;
        if ($gameNum) $game = $this->gameRepo->loadGame($this->project,$gameNum);
        else          $game = null;

        if (!$game) $game = $this->processNewGame($item);
        
        echo "{$item->gameNum} {$item->date} {$item->time} {$item->field} $item->homeTeam {$item->awayTeam}\n";
        
        // Done
        $em->flush();
        return;
        
        // Some checking
        if (!is_object($game)) return;
        $homeTeam = $game->getHomeTeam();
        $awayTeam = $game->getAwayTeam();
        echo "Home Team {$homeTeam->getTeamKey()} \n";
        echo "Away Team {$awayTeam->getTeamKey()} \n";

        return;
    }
    public function processNewGame($item)
    {
        $errors = array();

        $gameRepo = $this->gameRepo;

        // Need at least a home team for now
        $homeSchTeam = $gameRepo->loadSchTeam($this->project,$item->homeTeam);
        $awaySchTeam = $gameRepo->loadSchTeam($this->project,$item->awayTeam);
        if (!$homeSchTeam) return null;

        // Futz with game number
        $gameNum = $item->gameNum;
        if (!$gameNum)
        {
            $gameNum = $this->projectRepo->getNextSeqn($this->project,'game');
            $item->gameNum = $gameNum;
        }
        else $this->projectRepo->checkSeqn($this->project,'game',$gameNum);
        
        $date  = $item->date;
        $time  = $item->time;
        $field = $item->field;
        $gameNum = $item->gameNum;

        if (!$date)        $errors[] = 'Missing date';
        if (!$time)        $errors[] = 'Missing time';
        if (!$field)       $errors[] = 'Missing field';
        if (!$gameNum)     $errors[] = 'Missing game number';
        if (!$homeSchTeam) $errors[] = 'Missing home sch team';
      //if (!$awaySchTeam) $errors[] = 'Missing away sch team';

        if (count($errors)) return $this->processErrors($item,$errors);

        $game = new Game();
        $game->setProject($this->project);
        $game->setNum($gameNum);
        $game->setType('Game');
        $game->setDate($date);
        $game->setTime($time);
        $game->setFieldKey($field);

        $game->setAge   ($homeSchTeam->getAge());
        $game->setGender($homeSchTeam->getGender());
        $game->setOrgKey($homeSchTeam->getOrgKey());

        $game->setStatus('Active');
        
        $em = $this->getEntityManager();
        $em->persist($game);

        $this->processNewGameTeam($game,$homeSchTeam,'Home');
        $this->processNewGameTeam($game,$awaySchTeam,'Away');

        return $game;

    }
    protected function processNewGameTeam($game,$schTeam,$type)
    {
        if (!$schTeam) return null;

        $team = new GameTeam();
        $team->setProject($game->getProject());
        $team->setNum    ($game->getNum());
        $team->setGame   ($game);
        $team->setType   ($type);
        $team->setSchTeam($schTeam);
        $team->setTeamKey($schTeam->getTeamKey());
        $team->setAge    ($schTeam->getAge());
        $team->setGender ($schTeam->getGender());
        $team->setOrgKey ($schTeam->getOrgKey());
        $team->setStatus ('Active');

        $game->addGameTeam($team);
        
        $this->getEntityManager()->persist($team);

        return $team;
    }
    protected function processErrors($item,$errors)
    {
        echo "{$item->gameNum} {$item->date} {$item->time} {$item->field} $item->homeTeam {$item->awayTeam}\n";
        foreach($errors as $error)
        {
            echo $error . "\n";
        }
        return $errors;
    }
}
?>
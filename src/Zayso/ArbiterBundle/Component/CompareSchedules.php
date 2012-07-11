<?php
namespace Zayso\ArbiterBundle\Component;

use Zayso\CoreBundle\Component\Debug;

class CompareSchedules
{
    protected function gameIsDifferent($prop,$game1,$game2)
    {
        Debug::dump($game1);
        Debug::dump($game2);
        die("Property $prop\n");
    }
    protected function compareTeams($prop,$game1,$game2)
    {
        $getTeam = 'get' . $prop;
        
        $team1 = $game1->$getTeam();
        $team2 = $game2->$getTeam();
        
        if ($team1 == $team2) return;
        
        //echo "'$team1' '$team2'\n";
        
        if ($team2) return $this->gameIsDifferent($prop,$team1,$team2);
        
        if (strlen($team1) == 4) return;
        
        die('Team 1 ' . $team1 . "\n");
    }
    public function compareGame($game1,$game2)
    {
        if ($game1->getDate() != $game2->getDate()) $this->gameIsDifferent('Date',$game1,$game2);
        if ($game1->getTime() != $game2->getTime()) $this->gameIsDifferent('Time',$game1,$game2);
        
        // Problem with semi-finals
        $this->compareTeams('HomeTeam',$game1,$game2);
      
      //if ($game1->getHomeTeam() != $game2->getHomeTeam()) $this->gameIsDifferent('HomeTeam',$game1,$game2);
      //if ($game1->getAwayTeam() != $game2->getAwayTeam()) $this->gameIsDifferent('AwayTeam',$game1,$game2);
        
        if ($game1->getLevel() != $game2->getLevel()) $this->gameIsDifferent('Level',$game1,$game2);
        if ($game1->getSite () != $game2->getSite ()) $this->gameIsDifferent('Site', $game1,$game2);
        
    }
    public function compare($games1,$games2)
    {
        // Make sure each gets processed
        $games2x = array();
        foreach($games2 as $game) { $games2x[$game->getId()] = true; }
        
        foreach($games1 as $game1)
        {
            $id = $game1->getId();
            if (!isset($games2[$id]))
            {
                echo "No Les Game for Arbiter Game $id\n";
            }
            else
            {
                unset($games2x[$id]);
                $game2 = $games2[$id];
                
                $this->compareGame($game1,$game2);
            }
        }
        if (count($games2x))
        {
            'Les Games not found ' . implode(',',$games2x) . "\n";
        }
    }
}

?>

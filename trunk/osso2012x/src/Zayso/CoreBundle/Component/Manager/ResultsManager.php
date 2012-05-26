<?php
/* =========================================================
 * Focuses on calculating pool play results
 */
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

class ResultsManager extends ScheduleManager
{
    protected function calcPointsEarnedForTeam($game,$team1,$team2)
    {
        ///$team1 = $gameTeam1Rel->getTeam();
        ///$team2 = $gameTeam2Rel->getTeam();
        
        // Make scores are set
        $team1Goals = $team1->getGoalsScored();
        $team2Goals = $team2->getGoalsScored();
        if (($team1Goals === null) || ($team2Goals === null)) 
        {
            $team1->clearReportInfo();
            $team2->clearReportInfo();
            return;
        }
        $team1->setGoalsAllowed($team2Goals);
        $team2->setGoalsAllowed($team1Goals);
        
        $pointsMinus  = 0;
        $pointsEarned = 0;
        
        if ($team1Goals  > $team2Goals) $pointsEarned = 6;
        if ($team1Goals == $team2Goals) $pointsEarned = 3;
        
        // Shutout
        //if ($team2Goals == 0) $pointsEarned++;
        
        $maxGoals = $team1Goals;
        if ($maxGoals > 3) $maxGoals = 3;
        $pointsEarned += $maxGoals;
        
        $fudgeFactor = $team1->getFudgeFactor();
        $pointsEarned += $fudgeFactor;
        
      //if ($fudgeFactor < 0) $pointsMinus += abs($fudgeFactor);
      //$pointsMinus  += $fudgeFactor;
         
        $pointsMinus  -= ($team1->getSendoffs()    * 2);
        $pointsMinus  -= ($team1->getCoachTossed() * 3);
        $pointsMinus  -= ($team1->getSpecTossed()  * 0);
        
        $pointsEarned += $pointsMinus;
        
        $team1->setPointsMinus ($pointsMinus);
        $team1->setPointsEarned($pointsEarned);
    }
    // Points earned during a game
    public function calcPointsEarned($game)
    {
        $homeTeam = $game->getHomeTeam()->getTeam();
        $awayTeam = $game->getAwayTeam()->getTeam();
        
        if ($game->getReportStatus() == 'Reset')
        {
            $homeTeam->clearReportInfo();
            $awayTeam->clearReportInfo();
            return;
        }
        $this->calcPointsEarnedForTeam($game,$homeTeam,$awayTeam);
        $this->calcPointsEarnedForTeam($game,$awayTeam,$homeTeam);
    }
    public function getPools($games, $poolFilter = null)
    {
        $pools = array();
        foreach($games as $game)
        {
            $pool = $game->getPool();
            if ($game->isPoolPlay())
            {
                if (!$poolFilter || $poolFilter == substr($pool,8,1))
                {
                $pools[$pool]['games'][] = $game;
                
                $homeGameTeam = $game->getHomeTeam()->getTeam();
                $awayGameTeam = $game->getAwayTeam()->getTeam();

                $homePoolTeam = $homeGameTeam->getParent();
                $awayPoolTeam = $awayGameTeam->getParent();
                
                if ($homePoolTeam && $awayPoolTeam)
                {
                    if ($game->isPointsApplied())
                    {
                        $this->calcPoolTeamPoints($homeGameTeam,$homePoolTeam);
                        $this->calcPoolTeamPoints($awayGameTeam,$awayPoolTeam);
                    }
                    $pools[$pool]['teams'][$homePoolTeam->getId()] = $homePoolTeam;
                    $pools[$pool]['teams'][$awayPoolTeam->getId()] = $awayPoolTeam;
                }
            }}
        }
        ksort($pools);
        
        // Sort the teams by standing within each pool
        foreach($pools as $poolKey => $pool)
        {
            $teams = $pool['teams'];
            
            //sort
            usort($teams,array($this,'compareTeamStandings'));
            
            $pools[$poolKey]['teams'] = $teams;
        }
        return $pools;
    }
    protected function calcPoolTeamPoints($gameTeam,$poolTeam)
    {
        $poolTeam->addPointsEarned($gameTeam->getPointsEarned());   
        $poolTeam->addPointsMinus ($gameTeam->getPointsMinus());
        
        $poolTeam->addGoalsScored ($gameTeam->getGoalsScored());
        
        $goalsAllowed = $gameTeam->getGoalsAllowed();
        if ($goalsAllowed > 5) $goalsAllowed = 5;
        $poolTeam->addGoalsAllowed($goalsAllowed);
        
        $poolTeam->addCautions($gameTeam->getCautions());
        $poolTeam->addSendoffs($gameTeam->getSendoffs());
        
        $poolTeam->addCoachTossed($gameTeam->getCoachTossed());
        $poolTeam->addSpecTossed ($gameTeam->getSpecTossed());
        
        $poolTeam->addSportsmanship($gameTeam->getSportsmanship());
        
        if ($gameTeam->getGoalsScored() !== null)
        {
            $poolTeam->addGamesPlayed(1);
            if ($gameTeam->getGoalsScored() > $gameTeam->getGoalsAllowed()) $poolTeam->addGamesWon(1);
        }
    }
    protected function compareTeamStandings($team1,$team2)
    {
        // Points earned
        $pe1 = $team1->getPointsEarned();
        $pe2 = $team2->getPointsEarned();
        if ($pe1 < $pe2) return  1;
        if ($pe1 > $pe2) return -1;
        
        // Head to head
        
        // Games won
        $gw1 = $team1->getGamesWon();
        $gw2 = $team2->getGamesWon();
        if ($gw1 < $gw2) return  1;
        if ($gw1 > $gw2) return -1;
        
        // Sportsmanship deductions
        $pm1 = $team1->getPointsMinus();
        $pm2 = $team2->getPointsMinus();
        if ($pm1 < $pm2) return  1;
        if ($pm1 > $pm2) return -1;
         
        // Goals Allowed
        $ga1 = $team1->getGoalsAllowed();
        $ga2 = $team2->getGoalsAllowed();
        if ($ga1 < $ga2) return -1;
        if ($ga1 > $ga2) return  1;
        
        // Just the key
        $key1 = $team1->getKey();
        $key2 = $team2->getKey();
        
        if ($key1 < $key2) return -1;
        if ($key1 > $key2) return  1;
         
        return 0;
    }
}
?>

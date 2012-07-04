<?php
/* =========================================================
 * Focuses on calculating pool play results
 */
namespace Zayso\NatGamesBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Manager\ScheduleManager;

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
            $team1->clrData();
            $team2->clrData();
            return;
        }
        $team1->setGoalsAllowed($team2Goals);
        $team2->setGoalsAllowed($team1Goals);
        
        $pointsEarned = 0;
        
        if ($team1Goals  > $team2Goals) $pointsEarned = 6;
        if ($team1Goals == $team2Goals) $pointsEarned = 3;
        
        // Shutout
        if ($team2Goals == 0) $pointsEarned++;
        
        // Differential
        $diff = $team1Goals - $team2Goals;
        if ($diff < 0) $diff = 0;
        if ($diff > 3) $diff = 3;
        
        $pointsEarned += $diff;
        
        $fudgeFactor = $team1->getFudgeFactor();
        
        $pointsEarned += $fudgeFactor;
         
        $pointsEarned -= ($team1->getSendoffs()    * 1);
        $pointsEarned -= ($team1->getCoachTossed() * 1);
        $pointsEarned -= ($team1->getSpecTossed()  * 0);
        
        $team1->setPointsEarned($pointsEarned);
    }
    // Points earned during a game
    public function calcPointsEarned($game)
    {
        $homeTeam = $game->getHomeTeam()->getReport();
        $awayTeam = $game->getAwayTeam()->getReport();
        
        if ($game->getReportStatus() == 'Reset')
        {
            $homeTeam->clrData();
            $awayTeam->clrData();
            return;
        }
        $this->calcPointsEarnedForTeam($game,$homeTeam,$awayTeam);
        $this->calcPointsEarnedForTeam($game,$awayTeam,$homeTeam);
    }
    protected $pools;
    protected $gameTeams;
    
    protected function processPoolGame($game,$pool,$poolFilter)
    {
        if ($poolFilter && $poolFilter != substr($pool,8,1)) return;

        $this->pools[$pool]['games'][$game->getId()] = $game;
            
        if (!isset($this->pools[$pool]['teams'])) $this->pools[$pool]['teams'] = array();
                
        $homeTeamRelReport = $game->getHomeTeam()->getReport();
        $awayTeamRelReport = $game->getAwayTeam()->getReport();
                    
        $homePoolTeam = $game->getHomeTeam()->getTeam();
        $awayPoolTeam = $game->getAwayTeam()->getTeam();

        $homePoolTeamReport = $homePoolTeam->getReport();
        $awayPoolTeamReport = $awayPoolTeam->getReport();
                    
        // Updates list of teams
        if ($pool == substr($homePoolTeam->getKey(),0,strlen($pool)))
        {
            if (!isset($this->pools[$pool]['teams'][$homePoolTeam->getId()] ))
            {
                $homePoolTeamReport->addPointsEarned($homePoolTeam->getSfSP());   
                $this->pools[$pool]['teams'][$homePoolTeam->getId()] = $homePoolTeam;
            }
        }
        if ($pool == substr($awayPoolTeam->getKey(),0,strlen($pool)))
        {
            if (!isset($this->pools[$pool]['teams'][$awayPoolTeam->getId()] ))
            {
                $awayPoolTeamReport->addPointsEarned($awayPoolTeam->getSfSP());   
                $this->pools[$pool]['teams'][$awayPoolTeam->getId()] = $awayPoolTeam;
            }
        }
        // Calc the points
        $this->calcPoolTeamPoints($game,$homePoolTeam,$homePoolTeamReport,$homeTeamRelReport);
        $this->calcPoolTeamPoints($game,$awayPoolTeam,$awayPoolTeamReport,$awayTeamRelReport);
        
    }
    public function getPools($games, $poolFilter = null)
    {
        $this->pools = array();
        $this->gameTeams = array();
        
        foreach($games as $game)
        {
            if ($game->isPoolPlay())
            {
                $pool = $game->getPool();
                if (strlen($pool) == 9) $this->processPoolGame($game,$pool,$poolFilter);
                else
                {
                    $pool1 = substr($pool,0,9);
                    $this->processPoolGame($game,$pool1,$poolFilter);
                }                
            }
        }
        $pools = $this->pools;
        ksort($pools);
        
        // Sort the teams by standing within each pool
        foreach($pools as $poolKey => $pool)
        {
            $teams = $pool['teams'];
            
            $this->headToHeadGames = $pool['games'];
            
            //sort
            usort($teams,array($this,'compareTeamStandings'));
            
            $pools[$poolKey]['teams'] = $teams;
        }
        return $pools;
    }
    // Passed in report objects, gameTeam is actually gameTeamRelReport
    protected function calcPoolTeamPoints($game,$team,$poolTeam,$gameTeam)
    {   
        // Always do this even if not points not applied
        $poolTeam->addGamesTotal(1);
        
        // Only if points are applied
        if (!$game->isPointsApplied()) return;
        
        // Only if points are applied
        $poolTeam->addPointsEarned($gameTeam->getPointsEarned());   
     
        $poolTeam->addGoalsScored ($gameTeam->getGoalsScored());
        
        $goalsAllowed = $gameTeam->getGoalsAllowed();
      //if ($goalsAllowed > 5) $goalsAllowed = 5;
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
        if ($poolTeam->getGamesPlayed())
        {
            // The 6 comes from the six soccer fest points
            $wpf = $poolTeam->getPointsEarned() / (($poolTeam->getGamesPlayed() * 10) + 6);
            $wpf = sprintf('%.3f',$wpf);
        }
        else $wpf = null;
        
        $poolTeam->setWinPercent($wpf);
        
    }
    protected function compareHeadToHead($team1,$team2)
    {
        return 0;
    }
    protected function compareTeamStandings($team1x,$team2x)
    {
        $team1 = $team1x->getReport();
        $team2 = $team2x->getReport();
        
        // Points earned
        $pe1 = $team1->getPointsEarned(); // getWinPercent if uneven number of games
        $pe2 = $team2->getPointsEarned();
        if ($pe1 < $pe2) return  1;
        if ($pe1 > $pe2) return -1;
        
        // Head to head
        $cmp = $this->compareHeadToHead($team1,$team2);
        if ($cmp) return $cmp;
        
        // Sportsmanship
        $sp1 = $team1->getSportsmanship();
        $sp2 = $team2->getSportsmanship();
        
        if ($sp1 < $sp2) return  1;
        if ($sp1 > $sp2) return -1;
        
        // Goals Allowed
        $ga1 = $team1->getGoalsAllowed();
        $ga2 = $team2->getGoalsAllowed();
        if ($ga1 < $ga2) return -1;
        if ($ga1 > $ga2) return  1;
        
        // Just the key
        $key1 = $team1x->getKey();
        $key2 = $team2x->getKey();
        
        if ($key1 < $key2) return -1;
        if ($key1 > $key2) return  1;
         
        return 0;
    }
}
?>

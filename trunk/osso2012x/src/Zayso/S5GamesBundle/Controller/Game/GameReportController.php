<?php

namespace Zayso\S5GamesBundle\Controller\Game;

use Zayso\CoreBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;

class GameReportController extends BaseController
{
    protected function getGameManager()
    {
        return $this->get('zayso_core.game.manager');
    }
    public function reportAction(Request $request, $id = 0)
    {
        // Grab the game
        $manager = $this->getGameManager();
        $game = $manager->loadEventForId($id);
        if (!$game)
        {
            return $this->redirect($this->generateUrl('zayso_s5games_schedule_results'));
        }
        // Hokie default value
        if (!$game->getPointsApplied()) $game->setPointsApplied('Yes');
        
        $gameStatus = $game->getStatus();
        switch($gameStatus)
        {
            case 'Active': 
            case 'InProgress': 
                $game->setStatus('Played'); 
                $reportStatus = $game->getReportStatus();
              //die('Report Status ' . $reportStatus);
                break;
        }
        
        // Form
        $formType = $this->get('zayso_core.game.report.formtype');
        $form = $this->createForm($formType, $game);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $this->calcPointsEarned($game);
                
                $saved = $this->saveReport($request,$manager,$game);
                
                if ($saved) return $this->redirect($this->generateUrl('zayso_s5games_schedule_game_report',array('id' => $id)));
            }
        }
        
        // Gather up data
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['game'] = $game;
         
        return $this->render('ZaysoS5GamesBundle:Game:report.html.twig',$tplData);
    }
    protected function calcPointsEarnedForTeam($game,$gameTeam1Rel,$gameTeam2Rel)
    {
        $team1 = $gameTeam1Rel->getTeam();
        $team2 = $gameTeam2Rel->getTeam();
        
        // Make scores are set
        $team1Goals = $team1->getGoalsScored();
        $team2Goals = $team2->getGoalsScored();
        if (($team1Goals === null) || ($team2Goals === null)) 
        {
            //$team1->clearReportInfo();
            //$team2->clearReportInfo();
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
    protected function calcPointsEarned($game)
    {
        $homeTeam = $game->getHomeTeam();
        $awayTeam = $game->getAwayTeam();
        
        $this->calcPointsEarnedForTeam($game,$homeTeam,$awayTeam);
        $this->calcPointsEarnedForTeam($game,$awayTeam,$homeTeam);
    }
    protected function saveReport(Request $request, $manager, $game)
    {
        $reportStatus = $game->getReportStatus();
                
        // Need to be signed in
        if (!$this->isUser()) return false;
                
        // Prevent future?
        if ($reportStatus == 'Future') return false;
                
        // Turn pending into submitted
        if ($reportStatus == 'Reset')   $game->setReportStatus('Pending');
        if ($reportStatus == 'Pending') $game->setReportStatus('Submitted');
        
        // Only admin can approve
        if ($reportStatus == 'Approved') 
        {
            if (!$this->isAdmin()) return false;
        }
        
        // And save
        $manager->flush();
        
        return true;
        
    }
}
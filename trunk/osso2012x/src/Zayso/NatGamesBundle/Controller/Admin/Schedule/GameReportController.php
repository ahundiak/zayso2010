<?php

namespace Zayso\NatGamesBundle\Controller\Admin\Schedule;

use Zayso\CoreBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;

class GameReportController extends BaseController
{
    public function reportAction(Request $request, $id = 0)
    {
        // Grab the game
        $manager = $this->get('zayso_natgames.game.schedule.results.manager');
        $game = $manager->loadEventForId($id);
        if (!$game)
        {
            return $this->redirect($this->generateUrl('zayso_core_admin_schedule_results'));
        }
       // Hokie default value
        if (!$game->getPointsApplied()) $game->setPointsApplied('Yes');
        
        $gameStatus = $game->getStatus();
        switch($gameStatus)
        {
            case 'Active': 
            case 'InProgress': 
                $game->setStatus('Played'); 
              //$reportStatus = $game->getReportStatus();
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
                $manager->calcPointsEarned($game);
                
                $saved = $this->saveReport($request,$manager,$game);
                
                if ($saved) return $this->redirect($this->generateUrl('zayso_core_admin_schedule_game_report',array('id' => $id)));
            }
        }
        
        // Gather up data
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['game'] = $game;
         
        return $this->renderx('Admin\Schedule:report.html.twig',$tplData);
    }
    protected function saveReport(Request $request, $manager,$game)
    {
        $reportStatus = $game->getReportStatus();
                
        // Need to be signed in
        if (!$this->isUserScorer()) return false;
                
        // Prevent future?
        if ($reportStatus == 'Future') return false;
                
        // Turn pending into submitted
        if ($reportStatus == 'Reset')   $game->setReportStatus('Pending');
        if ($reportStatus == 'Pending') $game->setReportStatus('Submitted');
        
        // Only scorer admin can approve
        if ($reportStatus == 'Approved') 
        {
            if (!$this->isUserScorerx()) return false;
        }
        
        // And save, sort of fragile, form calls getReport but not setReport???
        $game->getHomeTeam()->saveReport();
        $game->getAwayTeam()->saveReport();
        
        $manager->flush();
        
        return true;
    }
}
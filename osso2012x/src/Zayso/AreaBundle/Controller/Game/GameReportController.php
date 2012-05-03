<?php

namespace Zayso\AreaBundle\Controller\Game;

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
            return $this->redirect($this->generateUrl('zayso_area_schedule_referee_list'));
        }
        
        // Form
        $formType = $this->get('zayso_core.game.report.formtype');
        $form = $this->createForm($formType, $game);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $saved = $this->saveReport($request,$manager,$game);
                
                if ($saved) return $this->redirect($this->generateUrl('zayso_area_schedule_game_report',array('id' => $id)));
            }
        }
        
        // Gather up data
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['game'] = $game;
         
        return $this->render('ZaysoAreaBundle:Game:report.html.twig',$tplData);
    }
    protected function saveReport(Request $request, $manager,$game)
    {
        $reportStatus = $game->getReportStatus();
                
        // Need to be signed in
        if (!$this->isUser()) return false;
                
        // Prevent future?
        if ($reportStatus == 'Future') return false;
                
        // Turn pending into submitted
        if ($reportStatus == 'Pending') $game->setReportStatus('Submitted');
        
        if ($request->request->get('submitted'))
        {
            if ($reportStatus == 'Approved') return false;
        }
        // Check for approved
        if ($request->request->get('approved'))
        {
            if (!$this->isAdmin()) false;
            
            $game->setReportStatus('Approved');
        }
        // And save
        $manager->flush();
        return true;
        
    }
}
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

        if ($this->isAdmin() && $request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                die('is valid');
                
                if ($request->request->get('update_submit'))
                {
                    $manager->flush();
                }
                if ($request->request->get('delete_submit'))
                {
                    $manager->remove($game);
                    $manager->flush();
                    return $this->redirect($this->generateUrl('zayso_area_schedule_referee_list'));
                }
                if ($request->request->get('clone_submit'))
                {
                    $gamex = $manager->cloneGame($game);
                    $manager->detach ($game);
                    $manager->persist($gamex);
                    $manager->flush();
                   
                    $id = $gamex->getId();
                }
                
                return $this->redirect($this->generateUrl('zayso_area_schedule_game_edit',array('id' => $id)));
            }
        }
        
        // Gather up data
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['game'] = $game;
         
        return $this->render('ZaysoAreaBundle:Game:report.html.twig',$tplData);
    }
}
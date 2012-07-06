<?php

namespace Zayso\NatGamesBundle\Controller\Schedule;

use Zayso\CoreBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;

class GameEditController extends BaseController
{
    public function editAction(Request $request, $id = 0)
    {
        // Grab the game
        $manager = $this->get('zayso_natgames.game.schedule.manager');
        $game = $manager->loadEventForId($id);
        if (!$game)
        {
            return $this->redirect($this->generateUrl('zayso_core_admin_schedule_list'));
        }
        die(' game ' . $id);
    }
}
    public function editActionx(Request $request, $id = 0)
    {
        // Grab the game
        $manager = $this->getScheduleManager();
        $game = $manager->loadEventForId($id);
        if (!$game)
        {
            return $this->redirect($this->generateUrl('zayso_area_schedule_referee_list'));
        }
        // Form
        $formType = new ScheduleGameEditFormType($manager->getEntityManager());
        $form = $this->createForm($formType, $game);

        if ($this->isAdmin() && $request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
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
         
        return $this->render('ZaysoAreaBundle:Schedule:edit.html.twig',$tplData);
    }
}
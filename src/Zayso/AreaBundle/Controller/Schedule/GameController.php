<?php

namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\AreaBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Format\HTML as FormatHTML;
use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\EventPerson;

use Zayso\AreaBundle\Component\FormType\Schedule\ScheduleGameEditFormType;

class GameController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_area.game.schedule.manager');
    }
    protected function getFormatHTML()
    {
        return $this->get('zayso_core.format.html');
    }
    protected function getGameViewHelper()
    {
        $format = $this->get('zayso_core.format.html');
        return new RefSchedGameViewHelper($format);
    }
    protected function getSearchViewHelper()
    {
        $format = $this->get('zayso_core.format.html');
        return new RefSchedSearchViewHelper($format);
    }
    public function editAction(Request $request, $id = 0)
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
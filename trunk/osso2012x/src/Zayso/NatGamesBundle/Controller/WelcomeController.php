<?php

namespace Zayso\NatGamesBundle\Controller;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        $tplData = $this->getTplData();
        return $this->render('NatGamesBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function homeAction()
    {
        // die($this->getRequest()->server->get('HTTP_USER_AGENT'));
        
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $todo = $user->getProjectPerson()->get('todo');
        if (isset($todo['projectPlans']) && $todo['projectPlans'])
        {
            $todo['projectPlans'] = false;
            $user->getProjectPerson()->set('todo',$todo);
            $this->getEntityManager()->flush();
            return $this->redirect($this->generateUrl('_natgames_project_plans'));
        }
        if (isset($todo['projectLevels']) && $todo['projectLevels'])
        {
            $todo['projectLevels'] = false;
            $user->getProjectPerson()->set('todo',$todo);
            $this->getEntityManager()->flush();

            $plans = $user->getProjectPerson()->get('plans');
            if (isset($plans['attend'])) $attend = strtolower($plans['attend']);
            else                         $attend = null;
            
            if (strpos($attend,'yes') !== false)
            {
                return $this->redirect($this->generateUrl('_natgames_project_levels'));
            }
        }
        $tplData = $this->getTplData();
        return $this->render('NatGamesBundle:Welcome:home.html.twig',$tplData);
    }
}

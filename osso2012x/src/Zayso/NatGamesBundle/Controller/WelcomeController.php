<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        $tplData = $this->getTplData();
        return $this->render('NatGamesBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function homeAction(Request $request)
    {   
        $em = $this->getAccountManager()->getEntityManager();
        $projectPerson = $this->getProjectPerson();
        
        // Really should not happen
        if (!$projectPerson)
        {
            $tplData = $this->getTplData();
            return $this->render('NatGamesBundle:Welcome:home.html.twig',$tplData);
        }

        $todo = $projectPerson->get('todo');
        
        if (isset($todo['projectPlans']) && $todo['projectPlans'])
        {
            $todo['projectPlans'] = false;
            $projectPerson->set('todo',$todo);
            $em->flush();
            return $this->redirect($this->generateUrl('natgames_project_plans'));
        }
        if (isset($todo['projectLevels']) && $todo['projectLevels'])
        {
            $todo['projectLevels'] = false;
            $projectPerson->set('todo',$todo);
            $em->flush();

            $plans = $projectPerson->get('plans');
            if (isset($plans['attend'])) $attend = strtolower($plans['attend']);
            else                         $attend = null;
            
            if (strpos($attend,'yes') !== false)
            {
                return $this->redirect($this->generateUrl('natgames_project_levels'));
            }
        }
        $tplData = $this->getTplData();
        return $this->render('NatGamesBundle:Welcome:home.html.twig',$tplData);
    }
}

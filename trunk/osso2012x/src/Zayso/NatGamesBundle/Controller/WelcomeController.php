<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\ZaysoBundle\Component\Debug;
use Zayso\ZaysoBundle\Entity\ProjectPerson;

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

        // Happens when area person first signs in
        if (!$projectPerson)
        {
            $project = $em->getReference('ZaysoBundle:Project',$this->getProjectId());
            $person  = $em->getReference('ZaysoBundle:Person', $this->getUser()->getPersonId());
            
            $projectPerson = new ProjectPerson();
            $projectPerson->setProject($project);
            $projectPerson->setPerson ($person);
            $projectPerson->setStatus ('Active');
            
            $todo = array('projectPlans' => true, 'openid' => true, 'projectLevels' => true);
            $projectPerson->set('todo',$todo);
            
            $em->persist($projectPerson);
            $em->flush();
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

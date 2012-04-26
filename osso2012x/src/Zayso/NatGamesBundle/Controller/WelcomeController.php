<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Entity\ProjectPerson;

use Zayso\CoreBundle\Controller\BaseController;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        if ($this->isUser())
        {
            return $this->redirect($this->generateUrl('zayso_natgames_home'));
        }
        $tplData = array();
        return $this->render('ZaysoNatGamesBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function homeAction(Request $request)
    {   
        // Ensure part of the project
        $manager = $this->getAccountManager();
        $projectPerson = $manager->addProjectPerson($this->getProjectId(),$this->getUser()->getPersonId());
        
        // Verify plans were set
        $plans = $projectPerson->get('plans');
        if (!isset($plans['attend']) || !$plans['attend'])
        {
            return $this->redirect($this->generateUrl('zayso_natgames_project_plans'));
        }
        // Make sure have levels
        if (isset($plans['will_referee']) && $plans['will_referee'] == 'Yes')
        {
            $levels = $projectPerson->get('levels'); //print_r($levels)
            if (!is_array($levels))
            {
                return $this->redirect($this->generateUrl('zayso_natgames_project_levels'));
            }
        }
        // Get prople for account
        $user = $this->getUser();
        $accountId = $user->getAccountId();
        $projectId = $this->getProjectId();
        $params = array('accountId' => $accountId,'projectId' => 0); // Want all projects
        $accountPersons = $manager->getAccountPersons($params);
        
        // And Render
        $tplData = array();
        $tplData['projectId']      = $projectId;
        $tplData['accountPersons'] = $accountPersons;
        
        return $this->render('ZaysoNatGamesBundle:Welcome:home.html.twig',$tplData);
    }
}

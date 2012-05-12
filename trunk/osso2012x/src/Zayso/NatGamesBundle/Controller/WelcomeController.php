<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Entity\ProjectPerson;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

class WelcomeController extends CoreBaseController
{
    public function welcomeAction()
    {
        if ($this->isUser() && !$this->isAdmin())
        {
            return $this->redirect($this->generateUrl('zayso_natgames_home'));
        }
        $tplData = array();
        return $this->render('ZaysoNatGamesBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function homeAction(Request $request)
    {   
        // Use the home manage
        $manager = $this->get('zayso_core.account.home.manager');
        
        // Ensure part of the project
        $user = $this->getUser();
        $accountId = $user->getAccountId();
        $personId  = $user->getPersonId();
        $projectId = $this->getProjectId();
        
        $projectPerson = $manager->addProjectPerson($projectId,$personId);
        
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
        $accountPersons = $manager->loadAccountPersons($accountId,$projectId);
        
        // And Render
        $tplData = array();
        $tplData['projectId']      = $projectId;
        $tplData['accountPersons'] = $accountPersons;
        
        return $this->render('ZaysoNatGamesBundle:Welcome:home.html.twig',$tplData);
    }
}

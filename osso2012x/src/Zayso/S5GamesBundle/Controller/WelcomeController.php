<?php

namespace Zayso\S5GamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        if ($this->isUser())
        {
            return $this->redirect($this->generateUrl('zayso_s5games_home'));
        }
        $tplData = array();
        return $this->render('ZaysoS5GamesBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function homeAction()
    {
        // Need to be signed in, security system does this
        if (!$this->isUser()) return $this->redirect($this->generateUrl('zayso_s5games_welcome'));
        
        // Ensure part of the project
        $manager = $this->getAccountManager();
        $projectPerson = $manager->addProjectPerson($this->getProjectId(),$this->getUser()->getPersonId());
        
        // Verify plans were set
        $plans = $projectPerson->get('plans');
        if (!isset($plans['willAttend']) || !$plans['willAttend'])
        {
            return $this->redirect($this->generateUrl('zayso_s5games_project_plans'));
        }
        
        // Get prople for account
        $user = $this->getUser();
        $accountId = $user->getAccountId();
        $projectId = $this->getProjectId();
        $params = array('accountId' => $accountId,'projectId' => $projectId); // Want all projects
        $accountPersons = $manager->getAccountPersons($params);
        
        // And Render
        $tplData = array();
        $tplData['projectId']      = $projectId;
        $tplData['accountPersons'] = $accountPersons;
        return $this->render('ZaysoS5GamesBundle:Welcome:home.html.twig',$tplData);
    }
    public function textalertsAction()
    { 
        $tplData = array();
        return $this->render('ZaysoS5GamesBundle:Welcome:text_alerts.html.twig',$tplData);
    }
    public function contactAction()
    { 
        $tplData = array();
        return $this->render('ZaysoS5GamesBundle:Welcome:contact.html.twig',$tplData);
    }
    public function scheduleAction()
    { 
        $tplData = array();
        return $this->render('ZaysoS5GamesBundle:Schedule:schedule.html.twig',$tplData);
    }
}
?>

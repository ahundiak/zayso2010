<?php

namespace Zayso\S5GamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

use Zayso\CoreBundle\Entity\Account;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        if ($this->isUser() && !$this->isAdmin())
        {
            return $this->redirect($this->generateUrl('zayso_core_home'));
        }
        $account = new Account();
        
        $signinFormType = $this->get('zayso_core.account.signin.formtype');

        $signinForm = $this->createForm($signinFormType, $account);
        
        $tplData = array();
        $tplData['signinForm'] = $signinForm->createView();
        return $this->renderx('Welcome:welcome.html.twig',$tplData);
    }
    public function textalertsAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:text_alerts.html.twig',$tplData);
    }
    public function contactAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:contact.html.twig',$tplData);
    }
    public function scheduleAction()
    { 
        $tplData = array();
        return $this->renderx('Schedule:schedule.html.twig',$tplData);
    }
    public function offlineAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:offline.html.twig',$tplData);
    }
}
?>

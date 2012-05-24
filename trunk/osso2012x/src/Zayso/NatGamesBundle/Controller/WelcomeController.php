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
            return $this->redirect($this->generateUrl('zayso_core_home'));
        }
        $tplData = array();
        return $this->render('ZaysoNatGamesBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function textalertsAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:textalerts.html.twig',$tplData);
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
}

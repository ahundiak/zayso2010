<?php

namespace Zayso\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

class PublicController extends CoreBaseController
{
    public function indexAction(Request $request)
    {
        $manager = $this->get('zayso_core.account.manager');
        $account = $manager->newAccount();
        
        $tplData = array();
        return $this->renderx('Public:index.html.twig',$tplData);
        
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
    public function shuttleAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:shuttle.html.twig',$tplData);
    }
    public function championsAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:champions.html.twig',$tplData);
    }
    public function offlineAction()
    { 
        $tplData = array();
        return $this->renderx('Welcome:offline.html.twig',$tplData);
    }
}

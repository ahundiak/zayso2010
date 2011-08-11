<?php

namespace Zayso\Area5CFBundle\Controller;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        $tplData = $this->getTplData();
        return $this->render('Area5CFBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function homeAction()
    {
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_area5cf_welcomex'));

        $tplData = $this->getTplData();
        return $this->render('Area5CFBundle:Welcome:home.html.twig',$tplData);
    }
}

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
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $tplData = $this->getTplData();
        return $this->render('NatGamesBundle:Welcome:home.html.twig',$tplData);
    }
}

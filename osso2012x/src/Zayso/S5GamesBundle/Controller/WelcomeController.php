<?php

namespace Zayso\S5GamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        $tplData = array();
        return $this->render('ZaysoS5GamesBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function homeAction()
    {
        $tplData = array();
        return $this->render('ZaysoAreaBundle:Welcome:home.html.twig',$tplData);
    }
    public function contactAction()
    { 
        $tplData = array();
        return $this->render('ZaysoAreaBundle:Contact:contact.html.twig',$tplData);
    }
}
?>

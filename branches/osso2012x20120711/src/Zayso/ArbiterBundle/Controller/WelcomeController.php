<?php
namespace Zayso\ArbiterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Welcome:welcome.html.twig',$tplData);
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

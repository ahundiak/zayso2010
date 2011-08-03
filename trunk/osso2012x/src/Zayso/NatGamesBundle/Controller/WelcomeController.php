<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
  public function welcomeAction()
  { 
    $data = array();
    return $this->render('NatGamesBundle:Welcome:welcome.html.twig',$data);
  }
}

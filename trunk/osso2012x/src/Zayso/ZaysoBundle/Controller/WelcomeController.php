<?php

namespace Zayso\ZaysoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
  public function welcomeAction()
  { 
    $env = $this->container->getParameter('kernel.environment');
    if ($env == 'prod') $base = 'http://zayso.org';
    else                $base = 'http://local.osso2012x.org/zayso';
    
    $data = array
    (
        'base' => $base,
    );
    return $this->render('ZaysoBundle:Welcome:welcome.html.twig',$data);
  }
}

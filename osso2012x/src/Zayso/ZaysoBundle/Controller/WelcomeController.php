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
    
    $statusList=array(
	'admin'    => 'Administrator',
	'operator' => 'Operator',
	'agent'    => 'Marketing Agent'
    );

    if (true) {
	$data['h1Text']='Pat Benatar';
	$data['status']='admin';
	$data['h1Class']='agentname';
        $data['h2Text'] = 'Administrator';
    } else {
	$data['h1Text']='Login Required.';
	$data['h1Class']='';
	$data['status']='';
        $data['h2Text'] = '';
    }
    $data['dater'] = date('r');
    
    $data['data'] = $data;
    
    return $this->render('ZaysoBundle:Welcome:welcome.html.twig',$data);
  }
}

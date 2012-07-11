<?php

namespace Zayso\AysoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\Common\Util\Debug;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AccountController extends Controller
{
  /**
   * @Route("/", name="_account")
   * @Template()
   */
  public function welcomeAction()
  {
    $accountRepo = $this->getDoctrine()->getRepository('AysoBundle:AccountItem');
    
    die('account welcome');
    $session = $this->get('session');

    // $session->set('zzz','ZZZ');

    $zzz = $session->get('zzz');

    $kernal = $this->get('kernel');
    //print_r($kernal);

    //$par = $this->container->getParameters();
    //print_r($par);
    
    //$mailer = $this->get('my_mailer');
    
    //Debug::dump($session);
    //die('ok');
    $user = $this->get('user');
    $format = new Format();
    
    $data = array
    (
      'user'   => $user, 
      'format' => $format,
      'zzz'    => $zzz,
    );
    return $this->render('AysoBundle:Welcome:welcome.html.twig',$data);
  }
}

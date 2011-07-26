<?php

namespace Zayso\AysoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\Common\Util\Debug;

class User
{
  public function getName() { return 'Guest'; }
}
class Format
{
  public function name($name)
  {
    return strtoupper($name);
  }
}
class BaseController extends Controller
{
  protected $user = null;
  
  protected function getUser()
  {
    if ($this->user) return $this->user;
    $this->user = new User();
    return $this->user;
  }
  public function get($id)
  {
    switch($id)
    {
      case 'user': return $this->getUser(); break;
    }
    return parent::get($id);
  }
}
class WelcomeController extends BaseController
{
  public function welcomeAction()
  {
    $session = $this->get('session');

    // $session->set('zzz','ZZZ');

    $zzz = $session->get('zzz');

    $kernal = $this->get('kernel');
    //echo get_class($kernal);
  //die('kernal');
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

<?php

namespace S5GamesApp\FrontEnd;

class SessionListener
{
  public function genSessionId()
  {
    if (isset($_COOKIE['s5games2011'])) return $_COOKIE['s5games2011'];

    $sessionId =  md5(uniqid());

    setcookie('s5games2011',$sessionId,mktime(0, 0, 0, 12, 31, 2015));
    
    return $sessionId;
  }
}
class Services extends \Cerad\Services
{
  public function newSession()
  {
    $em = $this->em;

    // Actually a repository
    $session = $em->getRepository('Session\SessionDataItem');
    $listener = new SessionListener();
    $session->setListener($listener);
    
    // Done
    return $this->data['session'] = $session;
  }
}
?>

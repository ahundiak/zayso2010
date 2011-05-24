<?php

namespace ZaysoApp\FrontEnd;

class Services extends \Cerad\Services
{
  protected function newSession()
  {
    $session = $this->getRepository('Session\SessionDataItem');
    $session->setListener($this);

    if (isset($_COOKIE['zayso2012']))
    {
      $session->setSessionId($_COOKIE['zayso2012']);
    }
    $this->data['session'] = $session;

    return $session;
  }
  public function genSessionId()
  {
    $sessionId = md5(uniqid());

    setcookie('zayso2012',$sessionId,time()+(3600*24*1000)); // Might need cookie path

    return $sessionId;

  }
}
?>

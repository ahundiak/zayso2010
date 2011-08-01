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
class Services extends \Cerad\ServicesS5Games
{
  protected $repoMap = array
  (
    'repoUser'    => array('em' => 'emUser',    'item' => 'S5Games\User\UserItem'),
    'repoGame'    => array('em' => 'emGame',    'item' => 'S5Games\Game\GameItem'),
    'repoAccount' => array('em' => 'emWork',    'item' => 'S5Games\Account\AccountItem'),
    'repoSession' => array('em' => 'emSession', 'item' => 'Session\SessionDataItem'),
  );

  public function newSession()
  {
    $em = $this->emSession;

    // Actually a repository
    $session = $em->getRepository('Session\SessionDataItem');
    $listener = new SessionListener();
    // $session->settListener($listener);
    
    // Done
    return $this->data['session'] = $session;
  }
  public function newUser()
  {
    $em = $this->emWork;
    
    $userData = $this->session->load('user');

    $accountId = $userData->accountId;
    
    $search = array('account_id' => $accountId);

    //$user = $em->getRepository('S5Games\User\UserItem')->findOneBy($search);

    $user = $this->repoUser->findOneBy($search);

    if (!$user) $user = new \S5Games\User\UserItem();
    
    return $this->data['user'] = $user;
  }

}
?>

<?php

namespace Area5CFApp\base;

class Services extends \Area5CF\base\Services
{
  protected $classNamesx = array
  (
    'request'  => 'Cerad\Request',
    'response' => 'Cerad\Response',
  );
  protected function init()
  {
    parent::init();
    $this->classNames = array_merge($this->classNames,$this->classNamesx);
  }
  public function newSession()
  {
    // Actually a repository
    $session = $this->sessionRepo;
    $session->setCookieName('Area5CF2012');
    
    // Done
    return $this->data['session'] = $session;
  }

  protected $userClassName = '\Area5CF\base\User';

  public function newUser()
  {
    $this->data['user'] = $user = new $this->userClassName($this);

    return $user;
    
    $userData = $this->session->load('user');

    $accountId = $userData->accountId;
    $memberId  = $userData->memberId;
    $projectId = $userData->projectId;

    if (!$accountId) return $user;

    $user->load($accountId,$memberId,$projectId);
    
    return $user;
    
    if ($accountId)
    {
      $search = array('account_id' => $accountId);

      $user = $this->userRepo->findOneBy($search);
    }
    if (!$user) $user = $this->userItem;
    
    return $this->data['user'] = $user;
  }

}
?>

<?php

namespace NatGamesApp\FrontEnd;

class Services extends \NatGames\base\Services
{
  protected $classNames = array
  (
    'request'  => 'Cerad\Request',
    'response' => 'Cerad\Response',
  );
  public function newSession()
  {
    // Actually a repository
    $session = $this->sessionRepo;
        
    // Done
    return $this->data['session'] = $session;
  }
  public function newUser()
  {
    $this->data['user'] = $user = new \NatGames\UserItem($this);
    
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

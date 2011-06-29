<?php
namespace NatGamesApp\Account\Signout;

class SignoutAction extends \NatGamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $session  = $this->services->session;
    $userData = $session->load('user');

    $userData->accountId = 0;
    $userData->memberId  = 0;
    $userData->personId  = 0;
    $userData->projectId = 0;

    $session->save($userData);

    return $this->redirect('welcome');
  }
}
?>

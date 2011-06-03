<?php
namespace S5GamesApp\Account\Signout;

class SignoutAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $session  = $this->services->session;
    $userData = $session->load('user');
    $userData->accountId = 0;
    $session->save($userData);

    return $this->redirect('welcome');
  }
}
?>

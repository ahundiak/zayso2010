<?php
namespace S5GamesApp\Account\Signin;

class SigninAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $sessionData = $session->load('account-signin');
    
    $view = new SigninView($this->services);
    $view->process(clone $sessionData);
    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $data = $session->load('account-signin');

    $userName = $request->getPostStr('signin_user_name');
    $userPass = $request->getPostStr('signin_user_pass');

    $data->userName = $userName;
    $data->errors = array();

    // Lookup account
    $accountRepo = $this->services->em->getRepository('S5Games\Account\AccountItem');

    $account = $accountRepo->findForUserName($userName,$userPass);
    
    if (!$account)
    {
      $data->errors = $accountRepo->getErrors();
      $session->save($data);
      return $this->redirect('account-signin');
    }
    $session->save($data);

    $data = $session->load('user');
    $data->setData();
    $data->accountId = $account->getId();
    $session->save($data);

    return $this->redirect('schedule-show');

  }
}
?>

<?php
namespace NatGamesApp\Account\Signin;

class SigninAction extends \NatGamesApp\FrontEnd\Action
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

    $userName  = $request->getPostStr('signin_user_name');
    $userPass  = $request->getPostStr('signin_user_pass');
    $projectId = $request->getPostint('project_id');

    $data->userName  = $userName;
    $data->projectId = $projectId;

    $data->errors = array();

    // Lookup account
    $accountRepo = $this->services->accountRepo;

    try {
      $account = $accountRepo->findForUserName($userName,$userPass);
    }
    catch (\Exception $e)
    {
      // Got some strange exceptions for s5games
      $session->clearCookie();
      return $this->redirect('account-signin');
    }
    if (!$account)
    {
      $data->errors = $accountRepo->getErrors();
      $session->save($data);
      return $this->redirect('account-signin');
    }
    // Have a successful sign in
    $session->save($data);
    $projectId = $data->projectId;

    $data = $session->load('user');
    $data->setData();

    $member = $account->getPrimaryMember();

    $data->accountId = $account->id;
    $data->memberId  = $member->id;
    $data->personId  = $member->person->id;
    $data->projectId = $projectId;

    $session->save($data);

    return $this->redirect('home');

  }
}
?>

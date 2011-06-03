<?php
namespace S5GamesApp\Account\Create;

class CreateAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $sessionData = $session->load('account-create');
    
    $view = new CreateView($this->services);
    $view->process(clone $sessionData);
    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $data = $session->load('account-create');

    // Extract
    $data->aysoid = $request->getPostStr('account_create_aysoid');
    $data->fname  = $request->getPostStr('account_create_fname');
    $data->lname  = $request->getPostStr('account_create_lname');
    $data->email  = $request->getPostStr('account_create_email');
    $data->phonec = $request->getPostStr('account_create_phonec');
    $data->uname  = $request->getPostStr('account_create_uname');

    $data->upass1 = $request->getPostStr('account_create_upass1');
    $data->upass2 = $request->getPostStr('account_create_upass2');

    $data->errors = array();

    // Create it
    $accountRepo = $this->services->em->getRepository('S5Games\Account\AccountItem');
    
    $account = $accountRepo->create($data);
    if (!$account)
    {
      $data->errors = $accountRepo->getErrors();
      //$data->upass1 = '';
      //$data->upass2 = '';
      $session->save($data);
      return $this->redirect('account-create');
    }
    // And redirect
    $data->upass1 = '';
    $data->upass2 = '';
    $session->save($data);

    // Auto sign in
    $data = $session->load('user');
    $data->setData();
    $data->accountId = $account->getId();
    $session->save($data);

    // die('Account created: ' . $account->getId());
    
    return $this->redirect('home');
  }
}
?>

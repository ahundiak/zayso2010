<?php
namespace NatGamesApp\Account\Create;

class CreateAction extends \NatGamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $viewData = $session->load('account-create');
    
    $view = new CreateView($this->services);
    $view->process(clone $viewData);
    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $data = $session->load('account-create');

    // Extract
    $data->uname  = $request->getPostStr('account_create_uname');
    $data->upass1 = $request->getPostStr('account_create_upass1');
    $data->upass2 = $request->getPostStr('account_create_upass2');

    $data->aysoid = $request->getPostStr('account_create_aysoid');
    $data->fname  = $request->getPostStr('account_create_fname');
    $data->lname  = $request->getPostStr('account_create_lname');
    $data->nname  = $request->getPostStr('account_create_nname');

    $data->email  = $request->getPostStr('account_create_email');
    $data->phonec = $request->getPostStr('account_create_phonec');

    $data->region    = $request->getPostInt('account_create_region');
    $data->refBadge  = $request->getPostStr('account_create_ref_badge');
    $data->projectId = $request->getPostInt('project_id');

    $data->errors = array();

    // Create it
    $accountRepo = $this->services->accountRepo;
    
    $account = $accountRepo->create($data);
    if (!$account)
    {
      $data->errors = $accountRepo->getErrors();
      $data->upass1 = '';
      $data->upass2 = '';
      $session->save($data);
      return $this->redirect('account-create');
    }
    // And redirect
    $data->upass1 = '';
    $data->upass2 = '';
    $session->save($data);

    // return $this->redirect('account-create');

    // Auto sign in
    $projectId = $data->projectId;
    
    $data = $session->load('user');
    $data->setData();

    $member = $account->getPrimaryMember();

    $data->accountId = $account->id;
    $data->memberId  = $member->id;
    $data->personId  = $member->person->id;
    $data->projectId = $projectId;

    $session->save($data);

    // print_r($data->getData());die();
    // die('Account created: ' . $account->getId());
    
    return $this->redirect('home');
  }
}
?>

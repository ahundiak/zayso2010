<?php
namespace S5GamesApp\Account\Update;

class UpdateAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $sessionData = $session->load('account-update');
    
    $view = new UpdateView($this->services);
    $view->process(clone $sessionData);
    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $data = $session->load('account-update');


    // Extract
    /*
    $account = new \S5Games\Account\AccountItem();

    $account->setId       ($request->getPostInt('account_update_id'));
    $account->setAysoid   ($request->getPostStr('account_update_aysoid'));
    $account->setFirstName($request->getPostStr('account_update_fname'));
    $account->setLastName ($request->getPostStr('account_update_lname'));
    $account->setEmail    ($request->getPostStr('account_update_email'));
    $account->setCellPhone($request->getPostStr('account_update_phonec'));
    $account->setUserName ($request->getPostStr('account_update_uname'));

    $account->setUserPass ($request->getPostStr('account_update_upass1'));
    $account->setUserPass2($request->getPostStr('account_update_upass2'));

    $account->setErrors(array());
*/

    // Extract
    $account = new \Cerad\DataItem();
    $account->id     = $request->getPostInt('account_update_id');
    $account->aysoid = $request->getPostStr('account_update_aysoid');
    $account->fname  = $request->getPostStr('account_update_fname');
    $account->lname  = $request->getPostStr('account_update_lname');
    $account->email  = $request->getPostStr('account_update_email');
    $account->phonec = $request->getPostStr('account_update_phonec');
    $account->uname  = $request->getPostStr('account_update_uname');

    $account->upass1 = $request->getPostStr('account_update_upass1');
    $account->upass2 = $request->getPostStr('account_update_upass2');

    $account->errors = array();

    $data->account = $account;
    $session->save($data);
    return $this->redirect('account-update');

    // Create it
    $accountRepo = $this->services->em->getRepository('S5Games\Account\AccountItem');
    
    $account = $accountRepo->update($data);die();
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

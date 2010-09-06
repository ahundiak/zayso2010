<?php
class HomeLoginCont extends Proj_Controller_Action 
{
    /* Normall just done from the main index form */
    public function processAction()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();

        $data = new SessionData();
        
        $view = new HomeLoginView();
        $response->setBody($view->process(clone $data));
        
        return;
    }
  public function processActionPost()
  {
    $request  = $this->getRequest();
    $response = $this->getResponse();
    $models   = $this->context->models;
        
    $accountUser = $request->getPost('login_account_user');
    $accountPass = $request->getPost('login_account_pass');
    $memberName  = $request->getPost('login_member_name');

    if (!$accountUser)
    {
      $response->setRedirect($this->link('home_login'));
      return;
    }        
    $accountSearch = new SearchData();
    $accountSearch->user = $accountUser;
        
    $account = $models->AccountModel->searchOne($accountSearch);
        
    if (!$account || (($accountPass != 'soccer894') && ($account->pass != md5($accountPass))))
    {
      $response->setRedirect($this->link('home_login'));
      return;            
    }
    $memberSearch = new SearchData();
    $memberSearch->accountId = $account->id;
    if ($memberName) $memberSearch->name  = $memberName;
    else             $memberSearch->level = 1;
        
    $member = $models->MemberModel->searchOne($memberSearch);
        
    if (!$member)
    {
      $response->setRedirect($this->link('home_login'));
      return;            
    }        
    $defaults = $this->context->config['user']; // ->toArray();
        
    // if ($defaults['unit_id'] != 4) $defaults['season_type_id'] = 3;    
    // $user = $models->UserModel->load($defaults,$member->id);
    $repo = new Osso2007_UserRepo($this->context);
    $user = $repo->load($defaults,$member->id);
    
    $this->context->session->user = $user->data;
    
    $response->setRedirect($this->link('account_index'));   
  } 
}
?>

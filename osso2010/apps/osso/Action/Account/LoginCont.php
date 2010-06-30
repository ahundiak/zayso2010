<?php
class Action_Account_LoginCont extends Action_Base_BaseCont
{
  protected $tplTitle = 'OSSO Account Login';
  protected $tplName  = 'Action/Account/Login.html.php';
  protected $tplPage  = 'Action/Master/SimplePage.html.php';

  protected $userMustBeLoggedIn = false;
  
  function executeGet()
  {
    return $this->renderPage();
  }
  function executePost()
  {
    // Ignore if already loggin in
    $user = $this->context->user;
    if ($user->isLoggedIn) return $this->redirect('index');
    
    $post = $this->context->requestPost;
    $params = array
    (
        'account_name' => $post->get('account_name'),
        'account_pass' => $post->get('account_pass'),
    );
    $direct  = new Osso_Account_AccountDirect($this->context);
    $results = $direct->authenticate($params);

    if ($results['success'] == true)
    {
      $user->changeUser($results['id'],true);
      
      $this->context->session->set('account-login',NULL);

      return $this->redirect('index');
    }
    // Remember attempted user login
    $this->context->session->set('account-login',$results['data']);

    return $this->redirect('account-login');
  }
}
?>

<?php
class Osso_User_UserLoginAction extends Osso_Base_BaseAction
{
  function executePost($args)
  {
    // Ignore if already loggin in
    $user = $this->context->user;
    // if ($user->isLoggedIn) return $this->result;
    
    $post = $this->context->requestPost;
    $params = array
    (
        'account_name' => $post->get('account_name'),
        'account_pass' => $post->get('account_pass'),
    );
    $direct = new Osso_Account_AccountDirect($this->context);
    $result = $direct->authenticate($params);

    if ($result->success == true)
    {
      $user->changeUser($result->id,true);
      
//      $this->context->session->set('account-login',NULL);

//      return $result;
    }
    // Remember attempted user login
    $this->context->session->set('account-login',$result->data);

    return $result;
  }
}
?>

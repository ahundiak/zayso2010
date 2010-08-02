<?php
class Action_Account_LogoutCont extends Action_Base_BaseCont
{
  function execute($args)
  {
    $user = $this->context->user;
    $user->logout();
    
    return $this->redirect('index');
  }
}

?>

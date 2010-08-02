<?php
class Osso_User_UserLogoutAction extends Osso_Base_BaseAction
{
  function execute($args)
  {
    $user = $this->context->user;
    $user->logout();
    
    return $this->result;
  }
}

?>

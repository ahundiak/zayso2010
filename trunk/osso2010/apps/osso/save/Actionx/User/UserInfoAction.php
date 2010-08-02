<?php
class Osso_User_UserInfoAction extends Osso_Base_BaseAction
{
  function execute($args)
  {
    // Ignore if already loggin in
    $user = $this->context->user;

    $result = $this->result;
    $result->user = $user->getData();

    return $result;
  }
}
?>

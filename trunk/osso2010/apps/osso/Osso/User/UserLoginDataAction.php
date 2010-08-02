<?php
class Osso_User_UserLoginDataAction extends Osso_Base_BaseAction
{
  function execute($args)
  {
    $result = $this->result;
    $user   = $this->context->user;

    $data   = $this->context->session->get('account-login');

    $result->data = $data;

    return $result;
  }
}
?>

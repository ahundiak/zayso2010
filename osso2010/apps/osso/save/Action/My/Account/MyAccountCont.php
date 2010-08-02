<?php
class Action_My_Account_MyAccountCont extends Action_Base_BaseCont
{
  protected $context;
  protected $tplTitle = 'OSSO My Account';
  protected $tplName  = 'Action/My/Account/MyAccount.html.php';

  function executeGet($args)
  {
    $user = $this->context->user;
    $direct = new Osso_Account_AccountDirect($this->context);
    $result = $direct->getAccountPersonData($user->id);

    $this->accountData = $result->row;
    
    return $this->renderPage();
  }
}

?>

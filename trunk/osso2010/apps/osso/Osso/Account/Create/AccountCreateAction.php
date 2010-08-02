<?php
class Osso_Account_Create_AccountCreateAction extends Osso_Base_BaseAction
{
  function executePost($args)
  {
    $params = $_POST;
    $direct = new Osso_Account_AccountDirect($this->context);
    $result = $direct->create($params);

    if ($result->success == true)
    {
      // $user->changeUser($result->id,true);
    }
    return $result;
  }
}
?>

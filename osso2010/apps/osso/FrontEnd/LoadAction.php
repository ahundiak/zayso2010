<?php
class FrontEnd_LoadAction
{
  protected $context;
  
  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  protected $argClassNames = array
  (
    'user-login'      => 'Osso_User_UserLoginAction',
    'user-login-data' => 'Osso_User_UserLoginDataAction',
    'user-logout'     => 'Osso_User_UserLogoutAction',
    'user-info'       => 'Osso_User_UserInfoAction',
    'user-menu'       => 'Osso_User_UserMenuAction',

    'account-create'  => 'Osso_Account_Create_AccountCreateAction',
   );
  function execute($args)
  {
    if ((!is_array($args) || count($args) < 1))
    {
      die("LoadActionx executed with no args\n");
    }
    $arg = array_shift($args);
    if (!isset($this->argClassNames[$arg]))
    {
      die("Invalid actionx arg: $arg\n");
    }
    $argClassName = $this->argClassNames[$arg];

    $action = new $argClassName($this->context);

    $result = $action->execute($args);

    header('Content-Type: application/json');
    echo json_encode($result);

  }
}
?>

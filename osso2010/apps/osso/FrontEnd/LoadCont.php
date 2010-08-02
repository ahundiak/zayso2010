<?php
class FrontEnd_LoadAction
{
  protected $context;
  
  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    //session_name('osso2010');
    //session_set_cookie_params(3600); // 0 Till browser is closed
    //session_start();

    date_default_timezone_set('US/Central');
  }
  protected $actionClassNames = array(
    'index-classic' => 'Action_Index_IndexCont',
    'index'         => 'Action_Index_IndexCont',
    'jquery'        => 'Action_JQuery_JQueryCont',
    'import'        => 'Action_Import_ImportCont',

    'account-login'  => 'Action_Account_LoginCont',
    'account-logout' => 'Action_Account_LogoutCont',

    'my-account'     => 'Action_My_Account_MyAccountCont',

    'person-edit'    => 'Action_Person_Edit_PersonEditCont',

  );
  function executeOld2()
  {
    $context = $this->context;
    $request = $context->request;
    $config  = $context->config;

    $action = $request->get('la',$config['load_action']);
    $method = $request->get('lm',$config['load_method']);

    $actionClassName = $this->actionClassNames[$action];

    $cont = new $actionClassName($context);
    $cont->$method();
  }
  function execute($args)
  {
    if ((!is_array($args) || count($args) < 1))
    {
      die("LoadAction Execute with no action name\n");
    }
    $action = array_shift($args);
    if (!isset($this->actionClassNames[$action]))
    {
      die("Invalid action: $action\n");
    }
    $actionClassName = $this->actionClassNames[$action];

    $cont = new $actionClassName($this->context);
    $cont->execute($args);
  }
  function executeOld()
  {
    ob_start();
    include 'Classic.html.php';
    $page = ob_get_clean();

    header('Content-type: text/html');
    echo $page;
  }
}
?>

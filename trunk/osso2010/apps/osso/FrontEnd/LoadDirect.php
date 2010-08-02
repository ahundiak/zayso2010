<?php
class FrontEnd_LoadDirect
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
    'account'        => 'Osso_Account_AccountDirect',
  );
  function execute($args)
  {
    if ((!is_array($args) || count($args) < 2))
    {
      die("LoadDirect executed with no args\n");
    }
    $arg = array_shift($args);
    if (!isset($this->argClassNames[$arg]))
    {
      die("Invalid direct arg: $arg\n");
    }
    $argClassName = $this->argClassNames[$arg];

    $argMethodName = array_shift($args);
    $params = $_POST;

    $direct = new $argClassName($this->context);

    $result = $direct->$argMethodName($params);

    header('Content-Type: application/json');
    echo json_encode($result);

  }
}
?>

<?php
class Cerad_FrontEnd_LoadAction
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
  );
  function execute($args)
  {
    if ((!is_array($args) || count($args) < 1))
    {
      die("LoadAction executed with no args\n");
    }
    $arg = array_shift($args);
    if (!isset($this->argClassNames[$arg]))
    {
      die("Invalid action arg: $arg\n");
    }
    $argClassName = $this->argClassNames[$arg];

    $action = new $argClassName($this->context);

    $result = $action->execute($args);

    // header('Content-type: text/html');

    echo $result;

  }
}
?>

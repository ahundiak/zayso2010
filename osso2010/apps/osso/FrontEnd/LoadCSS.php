<?php
class FrontEnd_LoadCSS
{
  protected $context;

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  protected $map = array
  (
    'osso' => 'css/osso.css.php',
  );

  function execute($args)
  {
    header('Content-type: text/css');
    foreach($args as $arg)
    {
      if (isset($this->map[$arg]))
      {
        include $this->map[$arg];
      }
    }
  }
}
?>

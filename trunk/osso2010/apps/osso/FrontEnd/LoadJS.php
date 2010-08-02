<?php
class FrontEnd_LoadJS
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
    'osso' => 'js/osso.js',
  );

  function execute($args)
  {
    header('Content-type: text/javascript');
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

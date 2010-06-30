<?php
class FrontEnd_Classic
{
  protected $context;
  
  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  function execute()
  {
    ob_start();
    include 'Classic.html.php';
    $page = ob_get_clean();

    header('Content-type: text/html');
    echo $page;
  }
}
?>

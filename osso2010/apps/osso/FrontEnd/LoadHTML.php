<?php
class FrontEnd_Action
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
    $f = $this->context->request->get('f');
    if($f) $files = explode(',',$f);
    else   $files = $this->files;

    ob_start();
    foreach($files as $file)
    {
      include 'css/' . $file . '.css';
    }
    header('text/css');
    ob_end_flush();
  }
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

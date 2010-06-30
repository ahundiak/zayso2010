<?php
class Cerad_FrontEnd_LoadCSS
{
  protected $context;
  protected $files;

  function __construct($context)
  {
    $this->context = $context;
  }
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
}
?>

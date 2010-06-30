<?php
class Cerad_FrontEnd_LoadJS
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
      $file = str_replace('-','/',$file);
      include 'js/' . $file . '.js';
    }
    header('text/js');
    ob_end_flush();
  }
}
?>

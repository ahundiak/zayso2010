<?php
class Cerad_FrontEnd_LoadCSS
{
  protected $context;
  protected $files;

  function __construct($context)
  {
    $this->context = $context;
  }
  function execute($args)
  {
    /*
  //$f = $this->context->request->get('f');
    $f = $args;
    if (!$f) return;

    if($f) $files = explode(',',$f);
    else   $files = $this->files;
*/
    $files=$args;

    ob_start();
    foreach($files as $file)
    {
      include 'css/' . $file . '.css.php';
    }
    header('text/css');
    ob_end_flush();
  }
}
?>

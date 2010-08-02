<?php
class Osso_Base_BaseTab
{
  protected $context;

  protected $webDir;
  protected $tplName = 'Base.html.php';

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->webDir = dirname($_SERVER['SCRIPT_NAME']);
  }
  protected function render($tplName = null)
  {
    if (!$tplName) $tplName = $this->tplName;

    ob_start();
    include $tplName;
    return ob_get_clean();
  }
  public function execute($args)
  {
    return $this->render();
  }
}
?>

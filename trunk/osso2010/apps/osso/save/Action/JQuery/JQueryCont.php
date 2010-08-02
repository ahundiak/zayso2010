<?php
class Action_JQuery_JQueryCont
{
  protected $context;

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function render($name)
  {
    ob_start();
    include $name;
    return ob_get_clean();
  }
  public function renderPage($name)
  {
    $this->content = $this->render($name);

    $html = $this->render($this->page);

    header('Content-type: text/html');
    echo $html;
  }
  function execute()
  {
    $this->page = 'Action/JQuery/Page.html.php';

    $test = $this->context->request->get('t');
    switch($test)
    {
      case 'upload':
        $this->title = 'JQuery Test Upload';
        $this->jsFiles = array('jquery-upload');
        $tplContent = 'Action/JQuery/Upload.html.php';
        break;

      case 'combo':
        $this->title = 'JQuery Test Combo';
        $this->jsFiles = array('jquery-combo');
        $tplContent = 'Action/JQuery/Combo.html.php';
        break;
    }

    $this->renderPage($tplContent);;
  }
}

?>

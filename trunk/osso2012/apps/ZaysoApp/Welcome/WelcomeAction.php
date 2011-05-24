<?php
namespace ZaysoApp\Welcome;

class WelcomeAction extends \ZaysoApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $view = new WelcomeView($this->services);
    $view->process();
    return;

    $response = $this->services->response;
    $request  = $this->services->request;

    $webBase = $request->webBase;

    $content = "<h1>Hell0 {$webBase}</h1>\n";
    $title = "Welcome";

    ob_start();
    include 'ZaysoApp/FrontEnd/PageTpl.html.php';
    $page = ob_get_contents();
    ob_end_clean();

    $response->setBody($page);
  }
}
?>

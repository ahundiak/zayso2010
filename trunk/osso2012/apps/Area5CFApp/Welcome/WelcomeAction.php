<?php
namespace Area5CFApp\Welcome;

class WelcomeAction extends \Area5CFApp\base\Action
{
  public function processGet($args)
  {
    // $user = $this->services->user;
    
    $data = $this->services->dataItem;

    $view = new WelcomeView($this->services);
    $view->process($data);
    return;
  }
  public function processPost($args)
  {
    return $this->redirect('welcome');
  }
}
?>

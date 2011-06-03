<?php
namespace S5GamesApp\Schedule\Show;

class ShowAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    
    $view = new ShowView($this->services);

    $view->process(array());

    return;
  }
  public function processPost($args)
  {
    die('Hello There Agsain');
    return $this->redirect('welcome');
  }
}
?>

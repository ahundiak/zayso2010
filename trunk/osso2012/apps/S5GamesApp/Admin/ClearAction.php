<?php
namespace S5GamesApp\Admin;

class ClearAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    if (isset($_COOKIE['s5games2011']))
    {
      setcookie('s5games2011','',time - 36000);
    }
    
    return $this->redirect('welcome');

  }
}
?>

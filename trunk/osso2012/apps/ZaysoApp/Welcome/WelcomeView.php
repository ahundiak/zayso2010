<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace ZaysoApp\Welcome;

class WelcomeView extends \ZaysoApp\FrontEnd\View
{
  protected $tplTitle = 'Welcome';
  protected $tplContent = 'ZaysoApp/Welcome/WelcomeTpl.html.php';

  public function process()
  {
    $this->renderPage();
  }
}
?>

<?php
namespace S5GamesApp\Account\Signin;

class SigninAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGetx($args)
  {
    $view = new WelcomeView($this->services);
    $view->process();
    return;
  }
  public function processGet($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $sessionData = $session->load('signin');

    $userName = $sessionData->userName;

    die('Signin get' . $userName);
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $sessionData = $session->load('signin');

    $userName = $request->getPostStr('signin_user_name');

    $sessionData->userName = $userName;

    $session->save($sessionData);
    
    // die('Signin Post ' . $userName);
    return $this->redirect('signin');
  }
}
?>

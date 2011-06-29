<?php
namespace NatGamesApp\ProjInfo\Plans;

class PlansAction extends \NatGamesApp\FrontEnd\Action
{
  protected $mustbeSignedIn = true;

  public function processGet($args)
  {
    $services = $this->services;
    $request  = $services->request;

    // $session  = $services->session;
    // $sessionData = $session->load('account-signin');

    $user = $this->services->user;
    $data = new \NatGames\DataItem();

    if (!$user->isSignedIn()) return $this->redirect('welcome');
    
    $plans = $user->getProjectPerson()->plans;

    if (!$plans) $plans = array();

    $data->plans = $plans;

    // Consider setting pool play levels based on account badge
    
    $view = new PlansView($this->services);
    $view->process($data);
    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;

    $plans = $_POST['plans'];
    if (!$plans) $plans = array();

    $user = $this->services->user;
    $projectPerson = $user->getProjectPerson();

    $projectPerson->plans = $plans;

    $user->saveProjectPerson($projectPerson);

    $url = 'projinfo-plans';
    if (isset($_POST['plans_submit_avail']))   $url .= '#plans-avail';
    if (isset($_POST['plans_submit_lodging'])) $url .= '#plans-lodging';

    return $this->redirect($url);

  }
}
?>

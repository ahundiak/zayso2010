<?php
namespace NatGamesApp\ProjInfo\RefLevel;

class RefLevelAction extends \NatGamesApp\FrontEnd\Action
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

    $refLevels = $user->getProjectPerson()->refLevels;

    if (!$refLevels) $refLevels = array();

    $data->refLevels = $refLevels;

    // Consider setting pool play levels based on account badge
    
    $view = new RefLevelView($this->services);
    $view->process($data);
    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;

    $refLevels = $_POST['ref_levels'];
    if (!$refLevels) $refLevels = array();

    $user = $this->services->user;
    $projectPerson = $user->getProjectPerson();

    $projectPerson->refLevels = $refLevels;

    $user->saveProjectPerson($projectPerson);

    $refLevels = $user->getProjectPerson()->refLevels;

    return $this->redirect('projinfo-reflevel');

  }
}
?>

<?php
namespace S5GamesApp\Schedule\Assign;

use \Cerad\DataItem as DataItem;

class AssignAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $services = $this->services;
    $session  = $services->session;
    $data     = $session->load('schedule-assign');

    if (count($args) == 2)
    {
      $data->gameId = (int)$args[0];
      $data->posId  = (int)$args[1];
      $data->aysoid = '';
      $data->assId  = 0;
      $data->status = 1;
      $session->save($data);
      return $this->redirect('schedule-assign');
    }

    $view = new AssignView($this->services);
    $view->process(clone $data);

    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $data = $session->load('schedule-assign');
    $data->errors = null;

    // Extract
    $data->gameId    = $request->getPostInt('game_id');
    $data->posId     = $request->getPostInt('pos_id');
    $data->assId     = $request->getPostInt('ass_id');
    $data->status    = $request->getPostInt('status');
    $data->aysoid    = $request->getPostStr('aysoid');

    // Basically just lookp a different referee
    $verify = $request->getPostStr('referee_verify');
    if ($verify)
    {
      $session->save($data);
      return $this->redirect('schedule-assign');
    }
    // Make sure have a good game and possible a position record
    $gameRepo = $this->services->repoGame;
    $game     = $gameRepo->find($data->gameId);
    if (!$game) $this->redirect('schedule-assign');
    
    $gamePerson = $game->getPerson($data->posId);

    // Deal with removals
    if ($data->status == 5)
    {
      if ($gamePerson)
      {
        $em = $this->services->emGame;
        $em->remove($gamePerson);
        $em->flush();
        $data->assId  = 0;
        $data->status = 1;
        $data->aysoid = '';
        $session->save($data);
      }
      return $this->redirect('schedule-assign');
    }
    // Make sure aysoid is valid
    $em  = $this->services->emVols;
    $vol = $em->find('S5Games\Vol\VolItem',$data->aysoid);
    if (!$vol)
    {
      $session->save($data);
      return $this->redirect('schedule-assign');

    }
    // Probably should make sure vol is duly certified

    // New record?
    if (!$gamePerson)
    {
      $gamePerson = new \S5Games\Game\GamePersonItem();
      $gamePerson->setGame($game);
      $game->addPerson($gamePerson);
    }
    $gamePerson->setAysoid($data->aysoid);
    $gamePerson->setPosId ($data->posId);
    $gamePerson->setAssId ($data->assId);
    $gamePerson->setStatus($data->status);

    $gamePerson->setFirstName($vol->fname);
    $gamePerson->setLastName ($vol->lname);
    $gamePerson->setNotes    ($vol->certRefereeDesc1);

    $region = $vol->orgKey;
    $region = (int)substr($region,1);
    $gamePerson->setRegion($region);
    
    $em = $this->services->emGame;  // Need to pull from game repo
    $em->persist($gamePerson);
    //die('Game is ' . $gamePerson->getGame()->getId());
    $em->flush();

    $data->assId  = 0;
    $data->status = 1;
    $data->aysoid = '';
    $session->save($data);

    return $this->redirect('schedule-assign');
  }
}
?>

<?php

namespace Zayso\NatGamesBundle\Controller;

class ScheduleController extends BaseController
{
  public function indexAction()
  { 
    $tplData = $this->getTplData();
    return $this->render('ZaysoNatGamesBundle:Schedule:schedule.html.twig',$tplData);
  }
}

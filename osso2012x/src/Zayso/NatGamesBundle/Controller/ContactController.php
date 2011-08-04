<?php

namespace Zayso\NatGamesBundle\Controller;

class ContactController extends BaseController
{
  public function contactAction()
  { 
    $tplData = $this->getTplData();
    return $this->render('NatGamesBundle:Contact:contact.html.twig',$tplData);
  }
}

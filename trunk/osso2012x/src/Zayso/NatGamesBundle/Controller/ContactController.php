<?php

namespace Zayso\NatGamesBundle\Controller;

class ContactController extends BaseController
{
  public function contactAction()
  { 
    $tplData = array();
    return $this->render('ZaysoNatGamesBundle:Contact:contact.html.twig',$tplData);
  }
}

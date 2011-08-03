<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
  public function contactAction()
  { 
    $data = array();
    return $this->render('NatGamesBundle:Contact:contact.html.twig',$data);
  }
}

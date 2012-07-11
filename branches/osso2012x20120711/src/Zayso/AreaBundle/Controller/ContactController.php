<?php

namespace Zayso\AreaBundle\Controller;

class ContactController extends BaseController
{
  public function contactAction()
  { 
    $tplData = array();
    return $this->render('ZaysoAreaBundle:Contact:contact.html.twig',$tplData);
  }
}

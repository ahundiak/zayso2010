<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
  public function createAction()
  { 
    $tplData = array();
    
    $session = $this->getRequest()->getSession();
    $accountCreateData = $session->get('accountCreateData');
    
    if ($accountCreateData) 
    {
      $errors = $session->getFlash('errors');
      $accountCreateData['errors'] = $errors;
    }
    else 
    {
    $accountCreateData = array
    (
      'uname'  => 'UName',
      'upass1' => '',
      'upass2' => '',
      'aysoid' => '12345678',
      'fname'  => 'FName',
      'lname'  => 'LName',
      'nname'  => 'NName',
      'email'  => 'Emails',
      'phonec' => 'PhoneC',
      'region' => 123,
      'refBadge' => 'Advanced',
      'errors'   => null, // array('E1','E2'),
    );
    }
    $refBadgePickList = array(
      'None'         => 'None',
      'Regional'     => 'Regional',
      'Intermediate' => 'Intermediate',
      'Advanced'     => 'Advanced',
      'National'     => "National",
      'National 2'   => 'National 2',
      'Assistant'    => 'Assistant',
      'U8 Official'  => 'U8',
    );
    $tplData['format'] = new \Zayso\ZaysoBundle\Component\Format\HTML();
    
    $tplData['accountCreateData'] = $accountCreateData;
    $tplData['refBadgePickList']  = $refBadgePickList;
    
    return $this->render('NatGamesBundle:Account:create.html.twig',$tplData);
  }
  public function createPostAction()
  {
    $request = $this->getRequest();
    
    $accountCreateData = $request->request->get('accountCreateData');
    
    $session = $this->getRequest()->getSession();
    $session->set('accountCreateData',$accountCreateData);
    
    $session->setFlash('errors',array('Posted errors'));
    
    //print_r($accountCreateData); die();
    
    return $this->redirect($this->generateUrl('_natgames_account_create'));
  }
}

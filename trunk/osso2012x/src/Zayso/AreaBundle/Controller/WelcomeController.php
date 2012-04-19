<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Zayso\AreaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController extends BaseController
{
    public function homeAction()
    {
        $user = $this->getUser();
        $accountId = $user->getAccountId();
      //$accountId = 26; // Sloans
        
        $manager = $this->getAccountManager();
        $accountPersons = $manager->getAccountPersons(array('accountId' => $accountId));
        
        $tplData = array();
        $tplData['accountPersons'] = $accountPersons;
        return $this->render('ZaysoAreaBundle:Welcome:home.html.twig',$tplData);
    }
    public function welcomeAction()
    {
        $tplData = array();
        return $this->render('ZaysoAreaBundle:Welcome:welcome.html.twig',$tplData);
    }
    public function contactAction()
    { 
        $tplData = array();
        return $this->render('ZaysoAreaBundle:Contact:contact.html.twig',$tplData);
    }
}
?>

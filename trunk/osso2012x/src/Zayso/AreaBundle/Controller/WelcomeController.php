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
        // Use the home manage
        $manager = $this->get('zayso_core.account.home.manager');
        
        $accountId = $this->getUser()->getAccountId();
        $projectId = $this->getCurrentProjectId();
        $account   = $manager->loadAccountWithPersons($projectId,$accountId);
        
        $tplData = array();
        $tplData['account'] = $account;
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
    public function offlineAction()
    { 
        $tplData = array();
        return $this->render('ZaysoAreaBundle:Welcome:offline.html.twig',$tplData);
    }
}
?>

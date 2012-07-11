<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Zayso\AreaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends BaseController
{
    public function indexAction()
    {
        $user = $this->getUser();
        $accountId = $user->getAccountId();
        $accountId = 26; // Sloans
        
        $manager = $this->getAccountManager();
        $accountPersons = $manager->getAccountPersons(array('accountId' => 26));
        
        $tplData = array();
        $tplData['accountPersons'] = $accountPersons;
        return $this->render('ZaysoAreaBundle:Profile:index.html.twig',$tplData);
    }
}
?>

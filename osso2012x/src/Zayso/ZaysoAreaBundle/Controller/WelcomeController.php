<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Zayso\ZaysoAreaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController extends BaseController
{
    public function welcomeAction()
    {
        $tplData = array();
        return $this->render('ZaysoAreaBundle:Welcome:welcome.html.twig',$tplData);

        return new Response('<h1>Welcome to the area bundle</h1>');
    }
}
?>

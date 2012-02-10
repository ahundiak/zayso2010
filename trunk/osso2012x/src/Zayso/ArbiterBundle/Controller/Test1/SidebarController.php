<?php
namespace Zayso\ArbiterBundle\Controller\Test1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SidebarController extends Controller
{
    public function sidebarAction()
    {
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Test1:sidebar.html.twig',$tplData);
    }
}
?>

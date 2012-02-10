<?php
namespace Zayso\ArbiterBundle\Controller\Test1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Test1:content.html.twig',$tplData);
    }
    public function sidebarAction()
    {
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Test1:sidebar.html.twig',$tplData);
    }
    public function sidebarjsAction()
    {
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Test1:sidebarjs.html.twig',$tplData);
    }
}
?>

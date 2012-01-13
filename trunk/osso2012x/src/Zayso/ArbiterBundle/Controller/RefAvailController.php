<?php
namespace Zayso\ArbiterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefController extends BaseController
{
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('zayso_arbiter_welcome'));
        
        $tplData = array();
        return $this->render('ZaysoArbiterBundle:Welcome:welcome.html.twig',$tplData);
    }
}
?>

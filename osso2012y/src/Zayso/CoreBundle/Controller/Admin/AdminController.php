<?php
namespace Zayso\CoreBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;


use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

class AdminController extends CoreBaseController
{
    public function indexAction(Request $request)
    {
        $tplData = array();
        return $this->renderx('Admin:index.html.twig',$tplData);        
    }
}
?>

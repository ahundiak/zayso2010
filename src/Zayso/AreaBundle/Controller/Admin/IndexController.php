<?php

namespace Zayso\AreaBundle\Controller\Admin;

use Zayso\AreaBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\Debug;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $tplData = array();
        return $this->render('ZaysoAreaBundle:Admin:index.html.twig',$tplData);
    }
}

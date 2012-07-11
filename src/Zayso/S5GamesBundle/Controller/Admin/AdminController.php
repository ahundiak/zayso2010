<?php

namespace Zayso\S5GamesBundle\Controller\Admin;

use Zayso\CoreBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\Debug;

class AdminController extends BaseController
{
    public function indexAction()
    {
        $tplData = array();
        return $this->render('ZaysoS5GamesBundle:Admin:index.html.twig',$tplData);
    }
}

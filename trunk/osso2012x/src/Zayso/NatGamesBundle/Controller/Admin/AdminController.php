<?php

namespace Zayso\NatGamesBundle\Controller\Admin;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\Debug;

class AdminController extends BaseController
{
    public function indexAction()
    {
        $tplData = $this->getTplData();
        return $this->render('ZaysoNatGamesBundle:Admin:index.html.twig',$tplData);
    }
}

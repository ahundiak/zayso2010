<?php

namespace Zayso\NatGamesBundle\Controller\Admin;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\Debug;

class AdminController extends BaseController
{
    public function indexAction()
    {
        $tplData = $this->getTplData();
        return $this->render('NatGamesBundle:Admin:index.html.twig',$tplData);
    }
}

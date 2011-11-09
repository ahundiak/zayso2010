<?php

namespace Zayso\NatGamesBundle\Controller\Admin;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\Debug;

class AdminController extends BaseController
{
    protected function isAuth()
    {
        $user = $this->getUser();
        if (!$user->isSignedIn()) return false;
        if (!$user->isAdmin   ()) return false;
        return true;
    }
    public function indexAction()
    {
        // Check auth
        if (!$this->isAuth()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $tplData = $this->getTplData();
        return $this->render('NatGamesBundle:Admin:index.html.twig',$tplData);
    }
}

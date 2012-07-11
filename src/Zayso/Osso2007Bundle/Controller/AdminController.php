<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;

class AdminController extends BaseController
{
    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_osso2007_welcome'));

        $tplData = $this->getTplData();
        return $this->render('Osso2007Bundle:Admin:index.html.twig',$tplData);
    }
}

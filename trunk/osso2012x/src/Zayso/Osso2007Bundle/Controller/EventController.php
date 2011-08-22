<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;

class EventController extends BaseController
{
    public function editAction($id = 0)
    {
        $user = $this->getUser();
        if (!$user->isAdmin()) return $this->redirect($this->generateUrl('_osso2007_welcome'));
die('id ' . $id);
        $tplData = $this->getTplData();
        return $this->render('Osso2007Bundle:Admin:index.html.twig',$tplData);
    }
}

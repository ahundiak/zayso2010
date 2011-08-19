<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;

class ImportController extends BaseController
{
    public function eaysoAction()
    {
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_osso2007_welcome'));
die('made it');
        $tplData = $this->getTplData();
        return $this->render('Osso2007Bundle:Admin:index.html.twig',$tplData);
    }
}

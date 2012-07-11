<?php

namespace Zayso\Area5CFBundle\Controller;

class ImportController extends BaseController
{
    public function importAction()
    {
        $tplData = $this->getTplData();
        return $this->render('Area5CFBundle:Import:import.html.twig',$tplData);
    }
    public function importPostAction()
    {
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_area5cf_welcomex'));
        
        $files = $this->getRequest()->files;

        $importFile = $files->get('import_file');
        
        echo $importFile->getClientOriginalName(),' ',$importFile->getPathname(); die();

        return $this->redirect($this->generateUrl('_area5cf_import'));
    }
}

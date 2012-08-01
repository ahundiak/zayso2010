<?php

namespace Zayso\AreaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

class HomeController extends CoreBaseController
{
    public function homeAction(Request $request)
    {        
        $tplData = array();
        
        return $this->renderx('Home:home.html.twig',$tplData);
    }
}

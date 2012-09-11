<?php
namespace Zayso\ArbiterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

class PublicController extends CoreBaseController
{
    public function indexAction(Request $request)
    {
        $tplData = array();
        
        return $this->renderx('Public:index.html.twig',$tplData);
    }
}
?>

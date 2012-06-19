<?php
namespace Zayso\NatGamesBundle\Controller\Admin\Person;

use Zayso\CoreBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends BaseController
{
    public function listAction(Request $request, $_format)
    {   
        if ($_format == 'html')
        {
            $manager = $this->get('zayso_natgames.person.manager');
            $persons = $manager->loadFlatPersonsForProject($this->getProjectId());
        
            $tplData = array();
            $tplData['persons'] = $persons;
        
            return $this->render('ZaysoNatGamesBundle:Admin:Person/list.html.twig',$tplData);
        }
        
        $export = $this->get('zayso_natgames.account.export');
        
        $outFileName = 'NatGamesPeople' . date('Ymd') . '.xls';
        
        $response = new Response();
        $response->setContent($export->generate());
        
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
        
        return $response;
        
     }
}

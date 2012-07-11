<?php
namespace Zayso\S5GamesBundle\Controller\Admin\Game;

use Zayso\CoreBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Game2011Controller extends BaseController
{
    public function listAction($_format)
    {
        $export = $this->get('zayso_s5games.game2011.export');
        
        $outFileName = 'S5Games2011Schedule' . date('Ymd') . '.xls';
        
        $response = new Response();
        $response->setContent($export->generate());
        
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
        
        return $response;
        
    }
}

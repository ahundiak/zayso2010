<?php

namespace Zayso\NatGamesBundle\Controller\Admin\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameSchedController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_core.game.schedule.manager');
    }
    protected $projectId     = 52;
    
    protected $sessionDataId = 'refSchSearchData2012';
    protected $searchFormId  = 'zayso_natgames.schedule.referee.search.formtype';
    protected $routeId       = 'zayso_core_schedule_referee_list';
    
    protected $csvTpl   = 'Schedule:referee.csv.php';
    protected $excelTpl = 'Schedule:referee.excel.php';
    protected $htmlTpl  = 'Schedule:referee.html.twig';
    
    protected $fileName = 'GameSchedule';
    
    public function listAction(Request $request, $_format, $search = null)
    {
        $manager = $this->getScheduleManager();
        
        $searchData = array(
            'projectId' => $this->projectId,
            'dates'     => array('20120705','20120706'),
            'ages'      => array('U10'),
        );
        $games = $manager->loadGames($searchData);
       
        $export = $this->get('zayso_natgames.game.export');
        
        $outFileName = 'GameSchedule' . date('YmdHi') . '.xls';
        
        $response = new Response();
        $response->setContent($export->generate($games));
        
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
        
        return $response;
    }
}

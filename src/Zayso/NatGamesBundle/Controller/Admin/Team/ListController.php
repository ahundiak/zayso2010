<?php

namespace Zayso\NatGamesBundle\Controller\Admin\Team;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_core.game.schedule.manager');
    }
    protected $projectId = 52;
    
    public function listAction(Request $request, $_format, $search = null)
    {
        $manager = $this->getScheduleManager();
        
        $teams = $manager->loadGameTeamsForProject($this->projectId);
        
        $export = $this->get('zayso_natgames.team.export');
        
        $outFileName = 'Teams' . date('YmdHi') . '.xls';
        
        $response = new Response();
        $response->setContent($export->generate($teams));
        
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
        
        return $response;
    }
}

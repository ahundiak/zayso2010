<?php

namespace Zayso\NatGamesBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamSchedController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_core.game.schedule.manager');
    }
    protected function initSearchData()
    {
        return array(
            'dows'     => array('FRI','SUN'),
            'time1'    => '0600',
            'time2'    => '2100',
            
            'coach'    => null,
            'official' => null,
            
            'team1'    => null,
            'team2'    => null,
            'team3'    => null,
       );
    }
    public function listAction(Request $request, $_format, $search = null)
    {
        $searchData = $this->initSearchData();
        
        // Pull from session if nothing was passed
        if (!$search) $search = $request->getSession()->get('teamSchSearchData');
        
        if ($search) $searchData = array_merge($searchData,json_decode($search,true));

        $searchFormType = $this->get('zayso_natgames.schedule.team.search.formtype');
        
        $searchForm = $this->createForm($searchFormType,$searchData);
        
        if ($request->getMethod() == 'POST')
        {
            $searchForm->bindRequest($request);

            if ($searchForm->isValid())
            {
                $search = $searchForm->getData();
                $search = json_encode($search);
                $request->getSession()->set('teamSchSearchData',$search);
                return $this->redirect($this->generateUrl('zayso_core_schedule_team_list',array('search' => $search)));
            }
        }
        $manager = $this->getScheduleManager();
        
        // Should projectId be in regular search data?  Probably
        $searchData['projectId'] = 52;
  
        $teamIds = array();
        $teamKeys = array('team1','team2','team3');
        foreach($teamKeys as $key)
        {
            if (isset($searchData[$key]) && $searchData[$key]) 
            {
                $teamId = (int)$searchData[$key];
                $teamIds[$teamId] = $teamId;
            }
        }
        if (count($teamIds)) $searchData['teamIds'] = $teamIds;
        
        $games = $manager->loadGames($searchData);

        if ($_format == 'csv')
        {
            $tplData = array();
            $tplData['games'] = $games;
            $response = $this->renderx('Schedule:team.csv.php',$tplData);
        
            $outFileName = 'TeamSchedule' . date('Ymd') . '.csv';
        
            $response->headers->set('Content-Type', 'text/csv;');
            $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
            return $response;
        }
        if ($_format == 'xls')
        {
            $tplData = array();
            $tplData['games'] = $games;
            $tplData['excel'] = $this->get('zayso_core.format.excel');
            $response = $this->renderx('Schedule:team.excel.php',$tplData);
        
            $outFileName = 'TeamSchedule' . date('YmdHi') . '.xls';
        
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
            return $response;
        }
        
        $tplData = array();
        $tplData['games']      = $games;
        $tplData['teamIds']    = $teamIds;
        $tplData['gameCount']  = count($games);
        $tplData['searchForm'] = $searchForm->createView();
        return $this->renderx('Schedule:team.html.twig',$tplData);        
    }
}

<?php

namespace Zayso\NatGamesBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MySchedController extends RefSchedController
{
    protected $sessionDataId = 'mySchSearchData2012';
    protected $searchFormId  = 'zayso_natgames.schedule.my.search.formtype';
    protected $routeId       = 'zayso_core_schedule_my_list';
    
    protected $csvTpl   = 'Schedule:referee.csv.php';
    protected $excelTpl = 'Schedule:referee.excel.php';
    protected $htmlTpl  = 'Schedule:my.html.twig';
    protected $fileName = 'MySchedule';
    
    public function listActionxxx(Request $request, $_format, $search = null)
    {
        // Verify user
        // if (!$this->isUser()) return $this->redirect($this->generateUrl('zayso_core_welcome'));
        
        $searchData = $this->initSearchData();
        
        // Pull from session if nothing was passed
        if (!$search) $search = $request->getSession()->get('mySchSearchData');
        
        if ($search) $searchData = array_merge($searchData,json_decode($search,true));

        $searchFormType = $this->get('zayso_natgames.schedule.my.search.formtype');
        
        // For selection list
        $searchFormType->setTeams  ($searchData['teams']);
        $searchFormType->setPersons($searchData['persons']);
        
        $searchForm = $this->createForm($searchFormType,$searchData);
        
        if ($request->getMethod() == 'POST')
        {
            $searchForm->bindRequest($request);

            if ($searchForm->isValid())
            {
                $search = $searchForm->getData();
                $search = json_encode($search);
                $request->getSession()->set('mySchSearchData',$search);
                return $this->redirect($this->generateUrl('zayso_core_schedule_my_list',array('search' => $search)));
            }
        }
        $manager = $this->getScheduleManager();
        
        // Should projectId be in regular search data?  Probably
        $searchData['projectId'] = 52;
        
        $games = $manager->loadGames($searchData);
        
        if ($_format == 'csv')
        {
            $tplData = array();
            $tplData['games'] = $games;
            $response = $this->renderx('Schedule:my.csv.php',$tplData);
        
            $outFileName = 'MySchedule' . date('YmdHi') . '.csv';
        
            $response->headers->set('Content-Type', 'text/csv;');
            $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
            return $response;
        }
        if ($_format == 'xls')
        {
            $tplData = array();
            $tplData['games'] = $games;
            $tplData['excel'] = $this->get('zayso_core.format.excel');
            $response = $this->renderx('Schedule:my.excel.php',$tplData);
        
            $outFileName = 'MySchedule' . date('YmdHi') . '.xls';
        
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
            return $response;
        }
        
        $tplData = array();
        $tplData['games']      = $games;
        $tplData['teamIds']    = $searchData['teamIds'];
        $tplData['personIds']  = $searchData['personIds'];
        $tplData['gameCount']  = count($games);
        $tplData['searchForm'] = $searchForm->createView();
        return $this->renderx('Schedule:my.html.twig',$tplData);       
    }
}

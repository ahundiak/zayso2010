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
        $searchData['projectId'] = 62;
  
        $teams = array();
        $teamKeys = array('team1','team2','team3');
        foreach($teamKeys as $key)
        {
            if (isset($searchData[$key]) && $searchData[$key]) 
            {
                $teamId = (int)$searchData[$key];
                $teams[$teamId] = $teamId;
            }
        }
        if (count($teams)) $searchData['teams'] = $teams;
        
        $games = $manager->loadGames($searchData);

        $games = $this->filterGames($games,$searchData['coach'],$searchData['official']);
        
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
        $tplData['games'] = $games;
        $tplData['teams'] = $teams;
        $tplData['gameCount'] = count($games);
        $tplData['searchForm'] = $searchForm->createView();
        return $this->renderx('Schedule:team.html.twig',$tplData);
        
    }
    protected function filterGames($games,$coach,$official)
    {
        // Do nothing if no filters
        if (!$coach && !$official) return $games;
        
        $coaches = array();
        $parts = explode(',',$coach);
        foreach($parts as $part) 
        {
            $part = trim($part);
            if ($part) $coaches[] = $part;
        }
        $officials = array();
        $parts = explode(',',$official);
        foreach($parts as $part) 
        {
            $part = trim($part);
            if ($part) $officials[] = $part;
        }
        $gamesx = array();
        foreach($games as $game)
        {
            $keep = false;
            $teams = $game->getTeams();
            foreach($teams as $gameTeamRel)
            {
                $homeTeam = $gameTeamRel->getTeam();
                $awayTeam = $gameTeamRel->getTeam();
                
//              $homeTeamKey = $homeTeam->getKey();
//              $awayTeamKey = $awayTeam->getKey();
                
                $homeTeamDesc = $homeTeam->getDesc();
                $awayTeamDesc = $awayTeam->getDesc();
               
                foreach($coaches as $coach)
                {
                    if (stripos($homeTeamDesc,$coach) !== false) $keep = true;
                    if (stripos($awayTeamDesc,$coach) !== false) $keep = true;
                }
            }
            $eventPersons = $game->getEventPersonsSorted();
            foreach($eventPersons as $eventPerson)
            {
                $person = $eventPerson->getPerson();
                if ($person)
                {
                    $name = $person->getPersonName();
                    foreach($officials as $official)
                    {
                        if (stripos($name,$official) !== false) $keep = true;
                    }
                }
            }
            
            if ($keep) $gamesx[] = $game;
        }
        return $gamesx;
    }
}

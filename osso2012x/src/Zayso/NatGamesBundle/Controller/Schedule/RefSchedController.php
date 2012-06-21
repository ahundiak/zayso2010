<?php

namespace Zayso\NatGamesBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefSchedController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_core.game.schedule.manager');
    }
    protected function initSearchData()
    {
        // Need teams for account
        $manager = $this->getScheduleManager();
        
        $user = $this->getUser();
        if (is_object($user)) $accountId = $user->getAccountId();
        else       $accountId = 0;
        
        // My Teams
        $items = $manager->loadTeamsForProjectAccount($this->projectId,$accountId);
        $teams   = array();
        $teamIds = array();
        foreach($items as $item)
        {
            $teams[$item->getId()] = $item->getDesc(); // For Pick List
            $teamIds[] = $item->getDesc();             // Selected teams
        }
        // My Persons
        $items = $manager->loadPersonsForProjectAccount(62,$accountId);
        $persons   = array();
        $personIds = array();
        foreach($items as $item)
        {
            $persons[$item->getId()] = $item->getPersonName(); // For Pick List
            $personIds[] = $item->getPersonName();             // Selected teams
        }
        return array(
            'dows'     => array('FRI','SUN'),
            'ages'     => array(),
            'genders'  => array(),
            'time1'    => '0600',
            'time2'    => '2100',
            'coach'    => null,
            'official' => null,
            
            'teams'    => $teams,
            'teamIds'  => $teamIds,
            'persons'  => $persons,
            'personIds'=> $personIds,
       );
    }
    protected $projectId     = 62;
    protected $sessionDataId = 'refSchSearchData';
    protected $searchFormId  = 'zayso_natgames.schedule.referee.search.formtype';
    protected $routeId       = 'zayso_core_schedule_referee_list';
    
    protected $csvTpl   = 'Schedule:referee.csv.php';
    protected $excelTpl = 'Schedule:referee.excel.php';
    protected $htmlTpl  = 'Schedule:referee.html.twig';
    protected $fileName = 'RefSchedule';
    
    public function listAction(Request $request, $_format, $search = null)
    {
        $searchData = $this->initSearchData();
        
        // Pull from session if nothing was passed
        if (!$search) $search = $request->getSession()->get($this->sessionDataId);
        
        if ($search) $searchData = array_merge($searchData,json_decode($search,true));

        $searchFormType = $this->get($this->searchFormId);
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
                $request->getSession()->set($this->sessionDataId,$search);
                return $this->redirect($this->generateUrl($this->routeId, array('search' => $search)));
            }
        }
        $manager = $this->getScheduleManager();
        
        // Should projectId be in regular search data?  Probably
        $searchData['projectId'] = $this->projectId;
        
        $games = $manager->loadGames($searchData);
        $games = $this->filterGames($games,$searchData['coach'],$searchData['official']);
        
        if ($_format == 'csv')
        {
            $tplData = array();
            $tplData['games'] = $games;
            $response = $this->renderx($this->csvTpl,$tplData);
        
            $outFileName = $this->fileName . date('Ymd') . '.csv';
        
            $response->headers->set('Content-Type', 'text/csv;');
            $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
            return $response;
        }
        if ($_format == 'xls')
        {
            $tplData = array();
            $tplData['games'] = $games;
            $tplData['excel'] = $this->get('zayso_core.format.excel');
            $response = $this->renderx($this->excelTpl,$tplData);
        
            $outFileName = $this->fileName . date('YmdHi') . '.xls';
        
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
            return $response;
        }
        
        $tplData = array();
        $tplData['games'] = $games;
        $tplData['gameCount'] = count($games);
        $tplData['teamIds']    = $searchData['teamIds'];
        $tplData['personIds']  = $searchData['personIds'];
        $tplData['searchForm'] = $searchForm->createView();
        return $this->renderx($this->htmlTpl,$tplData);
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

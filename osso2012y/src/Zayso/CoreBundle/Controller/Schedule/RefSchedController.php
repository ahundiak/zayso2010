<?php

namespace Zayso\CoreBundle\Controller\Schedule;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

use Symfony\Component\HttpFoundation\Request;

class RefSchedController extends CoreBaseController
{
    protected function initSearchData()
    {
        // Need teams for account
        $manager = $this->get('zayso_core.schedule.manager');
        
        $user = $this->getUser();
        if (is_object($user)) $personId = $user->getPersonId();
        else                  $personId = 0;
        
        // My Teams
        $items = $manager->loadTeamsForProjectPerson($this->projectId,$personId);
        $teams   = array();
        $teamIds = array();
        foreach($items as $item)
        {
            $teams[$item->getId()] = $item->getDesc(); // For Pick List
            $teamIds[] = $item->getDesc();             // Selected teams
        }
        
        // My Persons
        $items = $manager->loadPersonsForProjectPerson($this->projectId,$personId);
        $persons   = array();
        $personIds = array();
        foreach($items as $item)
        {
            $persons[$item->getId()] = $item->getPersonName(); // For Pick List
            $personIds[] = $item->getPersonName();             // Selected persons
        }
        // Dates
        $date1 = date('Ymd',time());
        $date2 = date('Ymd',time() + (60 * 60 * 24 * 14));
        
        //$desc1 = new \DateTime($date1); $desc1 = $desc1->format('D M d Y');
        //$desc2 = new \DateTime($date2); $desc2 = $desc2->format('D M d Y');

        //$date1x = array('date'=>$desc1,'year'=>substr($date1,0,4),'month'=>substr($date1,4,2),'day'=>substr($date1,6,2));
        //$date2x = array('date'=>$desc2,'year'=>substr($date2,0,4),'month'=>substr($date2,4,2),'day'=>substr($date2,6,2));
         
        return array(
            'dows'     => array('FRI','SUN'),
            'ages'     => array(),
            'genders'  => array(),
            'regions'  => array(),
            'time1'    => '0600',
            'time2'    => '2100',
            'date1'    => $date1,
            'date2'    => $date2,
            'coach'    => null,
            'official' => null,
            
            'teams'    => $teams,
            'teamIds'  => $teamIds,
            'persons'  => $persons,
            'personIds'=> $personIds,
       );
    }
    protected $projectId     = 80;
    protected $sessionDataId = 'refSchSearchData2012';
    protected $searchFormId  = 'zayso_core.schedule.referee.search.formtype';
    protected $routeId       = 'zayso_core_schedule_referee_list';
    
    protected $csvTpl   = 'Schedule:referee.csv.php';
    protected $excelTpl = 'Schedule:referee.excel.php';
    protected $htmlTpl  = 'ZaysoCoreBundle:Schedule:referee.html.twig';
    protected $fileName = 'RefSchedule';
    
    public function listAction(Request $request, $_format, $search = null)
    {
        // This keeps tripping me up since session data overrides
        // Sometimes need to sign out then sign back in again
        $searchData = $this->initSearchData();
        
        // Pull from session if nothing was passed
        $search = $request->getSession()->get($this->sessionDataId);
        
        if ($search) $searchData = array_merge($searchData,json_decode($search,true));
        
        // Need a better way to do the date stuff
        $date  = $searchData['date1'];
        $desc  = new \DateTime($date); $desc = $desc->format('D M d Y');
        $datex = array('date'=>$desc,'year'=>substr($date,0,4),'month'=>substr($date,4,2),'day'=>substr($date,6,2));
        $searchData['date1x'] = $datex;
        
        $date  = $searchData['date2'];
        $desc  = new \DateTime($date); $desc = $desc->format('D M d Y');
        $datex = array('date'=>$desc,'year'=>substr($date,0,4),'month'=>substr($date,4,2),'day'=>substr($date,6,2));
        $searchData['date2x'] = $datex;
         
        // Build the form
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
                
                // Remove the componet search parts
                $datex = $search['date1x'];
                $date  = $datex['year'] . $datex['month'] . $datex['day'];
                $search['date1'] = $date;
                unset($search['date1x']);
                
                $datex = $search['date2x'];
                $date  = $datex['year'] . $datex['month'] . $datex['day'];
                $search['date2'] = $date;
                unset($search['date2x']);
                
                $search = json_encode($search);
                $request->getSession()->set($this->sessionDataId,$search);
                
                return $this->redirect($this->generateUrl($this->routeId));
            }
        }
        $manager = $this->get('zayso_core.schedule.manager');
        
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
            $tplData['excel'] = $this->get('zayso_core.excel.format');
            $response = $this->renderx($this->excelTpl,$tplData);
        
            $outFileName = $this->fileName . date('YmdHi') . '.xls';
        
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
            return $response;
        }
        
        $tplData = array();
        $tplData['games']      = $games;
        $tplData['gameCount']  = count($games);
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

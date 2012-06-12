<?php

namespace Zayso\S5GamesBundle\Controller\Admin\Schedule;

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
        return array(
            'dows'     => array('FRI','SUN'),
            'ages'     => array(),
            'genders'  => array(),
            'time1'    => '0600',
            'time2'    => '2100',
            'coach'    => null,
            'official' => null,
       );
    }
    public function listAction(Request $request, $search = null)
    {
        $searchData = $this->initSearchData();
        
        // Pull from session if nothing was passed
        if (!$search) $search = $request->getSession()->get('refSchSearchData');
        
        if ($search) $searchData = array_merge($searchData,json_decode($search,true));

        $searchFormType = $this->get('zayso_s5games.schedule.search.formtype');
        
        $searchForm = $this->createForm($searchFormType,$searchData);
        
        if ($request->getMethod() == 'POST' && $request->request->get('schSearchSubmit'))
        {
            $searchForm->bindRequest($request);

            if ($searchForm->isValid())
            {
                // JSON_HEX_TAG ???
                // JSON_UNESCAPED_UNICODE 5.4
                // JSON_NUMERIC_CHECK Don't use, 0900 > 900
                $search = $searchForm->getData();
                $search = json_encode($search);
                $request->getSession()->set('refSchSearchData',$search);
                return $this->redirect($this->generateUrl('zayso_core_admin_schedule_referee_list',array('search' => $search)));
            }
        }
        // Make the list form
        $manager = $this->getScheduleManager();
        
        $searchData['projectId'] = 62;
        
        $games = $manager->loadGames($searchData);
        $games = $this->filterGames($games,$searchData['coach'],$searchData['official']);
        
        $listFormType = $this->get('zayso_s5games.admin.schedule.list.formtype');
        $listFormType->setManager($manager);
        $listFormType->setProjectId(62);
         
        $listForm = $this->createForm($listFormType,array('games' => $games));
        
        if ($request->getMethod() == 'POST' && $request->request->get('schListSubmit'))
        {
            $listForm->bindRequest($request);

            if ($listForm->isValid())
            {
                $manager->flush();
                
                return $this->redirect($this->generateUrl('zayso_core_admin_schedule_referee_list'));
            }
        }
        
        // And render
        $tplData = array();
        $tplData['games'] = $games;
        $tplData['gameCount'] = count($games);
        $tplData['listForm']   = $listForm->createView();
        $tplData['searchForm'] = $searchForm->createView();
        
        return $this->renderx('Admin/Schedule:referee.html.twig',$tplData);
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
                
                $homeTeamKey = $homeTeam->getKey();
                $awayTeamKey = $awayTeam->getKey();
                
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

<?php

namespace Zayso\S5GamesBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IappController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_core.game.schedule.manager');
    }
    public function listAction(Request $request, $_format)
    {
        $manager = $this->getScheduleManager();
        
        // Should projectId be in regular search data?  Probably
        $searchData = array();
        $searchData['projectId'] = 62;
        $searchData['dows']      = array('20120615','20120616','20120617');
        
        $games = $manager->loadGames($searchData);
        
        $tplData = array();
        $tplData['games'] = $games;
        
        $response = $this->renderx('Schedule:iapp.csv.php',$tplData);
        
        $outFileName = 'IappSchedule' . date('Ymd') . '.csv';
        
        $response->headers->set('Content-Type', 'text/csv;');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
        return $response;
        
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

<?php

namespace Zayso\Area5CFBundle\Controller;

use Zayso\ZaysoBundle\Entity\Game;
use Zayso\ZaysoBundle\Entity\GamePerson;

class ScheduleController extends BaseController
{
    public function editDivSchedAction()
    {
        $session = $this->getSession();
        $schedSearchData = $session->get('divSchedSearchData');

        if ($schedSearchData)
        {
            $errors = $session->getFlash('errors');
            $schedSearchData['errors'] = $errors;
        }
        else
        {
            $schedSearchData = array
            (
                'sortBy' => 1,
                'errors' => array(),
            );
        }
        if (!isset($schedSearchData['sortBy'])) $schedSearchData['sortBy'] = 1;
        if (!isset($schedSearchData['date1' ])) $schedSearchData['date1'] = '20110813';
        if (!isset($schedSearchData['date2' ])) $schedSearchData['date2'] = '20110830';

        // Grab the games
        $gameRepo = $this->getEntityManager()->getRepository('ZaysoBundle:Game');
        $games = $gameRepo->queryGames($schedSearchData);
        $gameCount = count($games);

        $teamPickList = $gameRepo->querySchTeamsPickList($schedSearchData,$games);

        // New games
        for($i = -1; $i > -10; $i--)
        {
            $game = new Game();
            $game->setNum($i);
            $game->setDate($schedSearchData['date2']);
            $games[] = $game;
        }
        // For view processor
        $this->schedSearchData = $schedSearchData;

        // Sort by
        $sortByPickList = array(1 => 'Date,Time,Field', 2 => 'Date,Field,Time', 3 => 'Date,Age,Time');

        // Dates
        $day  = new \DateInterval('P1D');
        $date = new \DateTime('08/01/2011');

        $datePickList = array();
        for($i = 0; $i < 100; $i++)
        {
            $datePickList[$date->format('Ymd')] = $date->format('M d, D');
            $date->add($day);
        }
        $qtr  = new \DateInterval('PT15M');
        $time = new \DateTime('07:00');
        $timePickList = array();
        $timePickList['TBD'] = 'TBD';
        $timePickList['BYE'] = 'BYE';
        for($i = 0; $i < 60; $i++)
        {
            $timePickList[$time->format('Hi')] = $time->format('h:i A');
            $time->add($qtr);
        }

        // Pass it all on
        $tplData = $this->getTplData();

        $tplData['games']     = $games;
        $tplData['gameCount'] = $gameCount;
        $tplData['teamPickList'] = $teamPickList;

        $tplData['ages']    = array('All','U05','U06','U07','U08','U10','U12','U14','U16','U19');
        $tplData['genders'] = array('All','Boys','Coed','Girls');
        $tplData['regions'] = array('All','R0160','R0498','R0894','R0914','R1174');
        $tplData['sortByPickList']  = $sortByPickList;
        $tplData['schedSearchData'] = $schedSearchData;
        $tplData['datePickList']    = $datePickList;
        $tplData['timePickList']    = $timePickList;
        $tplData['gen']             = $this;
        $tplData['postPath']        = '_area5cf_schedule_edit';
        $tplData['projectId']       = $this->getProjectId();

        return $this->render('Area5CFBundle:Schedule:div_edit.html.twig',$tplData);
    }
    public function editDivSchedPostAction()
    {
        $request = $this->getRequest()->request;
        $session = $this->getSession();

        $submit = $request->get('schedSearchSubmit');
        if ($submit)
        {
            $schedSearchData = $request->get('schedSearchData');

            $session->set('divSchedSearchData',$schedSearchData);

            return $this->redirect($this->generateUrl('_area5cf_schedule_edit'));
        }
        $submit = $request->get('schedEditSubmit');
        if (!$submit) return $this->redirect($this->generateUrl('_area5cf_schedule_edit'));

        $projectId = $request->get('projectId');
        $gameData  = $request->get('gameData');
        /*
        foreach($gameData as $num => $gamex)
        {
            echo "Game {$num} {$gamex['date']} {$gamex['time']} {$gamex['schTeams']['Home']} {$gamex['schTeams']['Away']}<br />\n";
        }*/
        return $this->redirect($this->generateUrl('_area5cf_schedule_edit'));
     }
    public function viewDivSchedAction()
    {
        $session = $this->getSession();
        $schedSearchData = $session->get('schedSearchData');

        if ($refSchedData)
        {
            $errors = $session->getFlash('errors');
            $refSchedData['errors'] = $errors;
        }
        else
        {
            $refSchedData = array
            (
                'sortBy' => 1,
                'errors' => array(),
            );
        }
        if (!isset($refSchedData['sortBy'])) $refSchedData['sortBy'] = 1;
        if (!isset($refSchedData['date1' ])) $refSchedData['date1'] = '20110813';
        if (!isset($refSchedData['date2' ])) $refSchedData['date2'] = '20110830';
        
        // Grab the games
        $gameRepo = $this->getEntityManager()->getRepository('ZaysoBundle:Game');
        $games = $gameRepo->queryGames($refSchedData);


        // For view processor
        $this->refSchedData = $refSchedData;

        // Sort by
        $sortByPickList = array(1 => 'Date,Time,Field', 2 => 'Date,Field,Time', 3 => 'Date,Age,Time');

        // Dates
        $day  = new \DateInterval('P1D');
        $date = new \DateTime('08/01/2011');
        
        $dates = array();
        for($i = 0; $i < 100; $i++)
        {
            $dates[$date->format('Ymd')] = $date->format('M d, D');
            $date->add($day);
        }

        // Pass it all on
        $tplData = $this->getTplData();

        $tplData['games']     = $games;
        $tplData['gameCount'] = count($games);

        $tplData['ages']    = array('All','U05','U06','U07','U08','U10','U12','U14','U16','U19');
        $tplData['genders'] = array('All','Boys','Coed','Girls');
        $tplData['regions'] = array('All','R0160','R0498','R0894','R0914','R1174');
        $tplData['sortByPickList'] = $sortByPickList;
        $tplData['refSchedData']   = $refSchedData;
        $tplData['datesPickList']  = $dates;
        $tplData['gen']  = $this;
        return $this->render('Area5CFBundle:Schedule:schedule.html.twig',$tplData);
    }
    public function viewRefSchedPostAction()
    {
        $request = $this->getRequest();
        $session = $this->getSession();


        $refSchedData = $request->request->get('refSchedData');

        $session->set('refSchedData',$refSchedData);

        return $this->redirect($this->generateUrl('_area5cf_schedule'));
    }
    /* ===================================================================
     * Assorted call backs for displaying information
     */
    public function genAgeCheckBox($age)
    {
        if (isset($this->schedSearchData['ages'][$age])) $checked = 'checked="checked"';
        else                                             $checked = null;
        
        $html = sprintf('%s<br /><input type="checkbox" name="schedSearchData[ages][%s]" value="%s" %s />',$age,$age,$age,$checked);
        return $html;
    }
    public function genGenderCheckBox($gender)
    {
        if (isset($this->schedSearchData['genders'][$gender])) $checked = 'checked="checked"';
        else                                                   $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="schedSearchData[genders][%s]" value="%s" %s />',
                $gender,$gender,substr($gender,0,1),$checked);
        return $html;
    }
    public function genRegionCheckBox($region)
    {
        if (isset($this->schedSearchData['regions'][$region])) $checked = 'checked="checked"';
        else                                                   $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="schedSearchData[regions][%s]" value="AYSO%s" %s />',$region,$region,$region,$checked);
        return $html;
    }
    public function genTeam($team)
    {
        if (!$team) return '';

        $key = $team->getTeamKey();
        if (!$key) return $key;

        $key = sprintf('%s-%s-%s %s',substr($key,0,5),substr($key,5,4),substr($key,9,2),substr($key,12));
        return $key;
    }
    public function genReferees($game)
    {
        $refs = array();
        $types = array('CR','AR1','AR2');
        foreach($types as $type)
        {
            $ref = $game->getGamePersonForType($type);
            if (!$ref)
            {
                $ref = new GamePerson();
                $ref->setType($type);
            }
            $refs[$type] = $ref;
        }
        $html = '<table>';
        foreach($refs as $ref)
        {
            $type = $ref->getType();
            $name = $ref->getLastName();
            $row = sprintf('<tr><td>%s</td><td>%s</td></tr>',$type,$name);
            $html .= $row;
        }
        $html .= '</table>';
        return $html;
    }
    protected $lastGameDate;
    public function genGameDateBreak($game)
    {
        if ($game->getNum() == -1)
        {
            $this->lastGameDate = $game->getDate();
            return true;
        }
        if (!$this->lastGameDate)
        {
            $this->lastGameDate = $game->getDate(); 
            return false;
        }
        if ($this->lastGameDate == $game->getDate()) return false;

        $this->lastGameDate = $game->getDate();
        return true;
    }
}

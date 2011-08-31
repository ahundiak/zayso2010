<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;
use Zayso\Osso2007Bundle\Component\HTML as BaseFormat;

class Format extends BaseFormat
{
    protected $searchData = array();

    public function genCheckBox($name,$data,$key,$desc)
    {
        if (isset($data[$key]) && $data[$key]) $checked = 'checked="checked"';
        else                                   $checked = null;

        $html = sprintf('<input type="checkbox" name="%s[%s]" value="%s" %s />%s',
                $name,$key,$key,$checked,$desc);
        return $html;
    }
}
class EventController extends BaseController
{
    /* ==================================================================
     * Kind of a messy function designed to build an array which can be used
     * for searching by the game manager
     * 
     * searchData - Posted by the form itself, only selected check boxes will be present
     * xxxKeys - List of available keys for a given category, used to process the All check box
     *           Currently does double duty for building the check boxes themselves
     *           The regionKeys in particular should be generated from the project
     */
    protected function getSearchParams($searchData,$regionKeys,$ageKeys,$genderKeys)
    {
        // Start with regions
        if (isset($searchData['regions'])) $regions = $searchData['regions'];
        else                               $regions = null;
        if ($regions)
        {
            if (isset($regions['All']))
            {
                $regions = array();
                foreach($regionKeys as $regionKey)
                {
                    if ($regionKey != 'All') $regions[$regionKey] = $regionKey;
                }
            }
            // else use posted data
        }
        // Pretty much the same for ages
        if (isset($searchData['ages'])) $ages = $searchData['ages'];
        else                            $ages = null;
        if ($ages)
        {
            if (isset($ages['All']))
            {
                $ages = array();
                foreach($ageKeys as $ageKey)
                {
                    if ($ageKey != 'All') $ages[$ageKey] = $ageKey;
                }
            }
            // else use posted data
        }
        // For genders, have a description different from the keys
        if (isset($searchData['genders'])) $genders = $searchData['genders'];
        else                               $genders = null;
        if ($genders)
        {
            if (isset($genders['All']))
            {
                $genders = array();
                foreach($genderKeys as $key => $desc)
                {
                    if ($key != 'All') $genders[$key] = $key;
                }
            }
            // else use posted data
        }
        if (isset($genders['B'])) $genders['C'] = 'C';
        return array
        (
            'regions' => $regions,
            'ages'    => $ages,
            'genders' => $genders,
        );
        // Debug::dump($regions); die();
    }
    /* =================================================================
     * ages,genders,regions are key/description for all possible items
     *
     * searchData starts with posted check boxes
     * add to searchData and relevant game infomation
     * if any All boxes are checked then use the key/desc list to set all the values
     *
     * Kind of a mess but seems to work
     */
    public function editAction($id = 0)
    {
        // Permissions
        $user = $this->getUser();
        if (!$user->isAdmin()) return $this->redirect($this->generateUrl('_osso2007_welcome'));

        // Pull out any saved search criteria
        $session = $this->getSession();
        $searchData = $session->get('gameEditSearchData');
        if (!is_array($searchData)) $searchData = array();

        // Eventually come from the project
        $ages  = array('All' => 'All',
            'U05' => 'U05', 'U06' => 'U06', 'U07' => 'U07',
            'U08' => 'U08', 'U10' => 'U10', 'U12' => 'U12',
            'U14' => 'U14', 'U16' => 'U16', 'U19' => 'U19');

        $regions = array('All' => 'All',
            'R0160' => 'R0160', 'R0498' => 'R0498', 'R0557' => 'R0557',
            'R0991' => 'R0991', 'R0894' => 'R0894', 'R0914' => 'R0914',
            'R1174' => 'R1174', 'R1564' => 'R1565');

        $genders = array(
            'All' => 'All',
            'B'   => 'Boys',
            'C'   => 'Coed',
            'G'   => 'Girls',
        );

        // Load in the data
        $gameManager = $this->getGameManager();
        $gameId = $id;

        if ($gameId) $game = $gameManager->loadGameForId($gameId);
        else         $game = null;

        if (!$game)
        {
            // Think we can just create empty team and game teams
            die('New game not yet handled');
        }
        // Rest of search data
        $projectId = $game->getProjectId();

        // Make sure game info is set
        $regionKey = $game->getRegionKey();      if ($regionKey) $searchData['regions'][$regionKey] = $regionKey;
        $regionKey = $game->getFieldRegionKey(); if ($regionKey) $searchData['regions'][$regionKey] = $regionKey;

        foreach($game->getGameTeams() as $team)
        {
            $regionKey = $team->getRegionKey(); if ($regionKey) $searchData['regions'][$regionKey] = $regionKey;
            $genderKey = $team->getGenderKey(); if ($genderKey) $searchData['genders'][$genderKey] = $genderKey;
            $ageKey    = $team->getAgeKey();    if ($ageKey)    $searchData['ages']   [$ageKey]    = $ageKey;
        }

        // Process the All check
        $searchData = $this->getSearchParams($searchData,$regions,$ages,$genders);
        $searchData['projectId'] = $projectId;

        // Put together template
        $tplData = array();
        $tplData['user']   = $user;
        $tplData['format'] = new Format();
        
        $tplData['game'] = $game;
        $tplData['searchData'] = $searchData;
        
        $tplData['datePickList']    = $gameManager->getDatePickList($projectId);
        $tplData['timePickList']    = $gameManager->getTimePickList($projectId);

        $tplData['fieldPickList']   = $gameManager->getFieldPickList  ($searchData);
        $tplData['schTeamPickList'] = $gameManager->getSchTeamPickList($searchData);

        $tplData['ages']    = $ages;
        $tplData['genders'] = $genders;
        $tplData['regions'] = $regions;
        
        return $this->render('Osso2007Bundle:Game:edit.html.twig',$tplData);
    }
    public function editPostAction($id)
    {
        // Permissions
        $user = $this->getUser();
        if (!$user->isAdmin()) return $this->redirect($this->generateUrl('_osso2007_welcome'));

        // See what was pushed
        $request = $this->getRequest();
        $session = $this->getSession();

        if ($request->request->get('search_submit'))
        {
            $searchData = $request->request->get('searchData');
            $session->set('gameEditSearchData',$searchData);
            return $this->redirect($this->generateUrl('_osso2007_event_edit', array('id' => $id)));
        }
        if ($request->request->get('update_submit'))
        {
            $editData = $request->request->get('editData');
            $this->updateGame($editData);
            return $this->redirect($this->generateUrl('_osso2007_event_edit', array('id' => $id)));
        }
        if ($request->request->get('clone_submit'))
        {
            $editData = $request->request->get('editData');
            $id = $this->cloneGame($editData);
            return $this->redirect($this->generateUrl('_osso2007_event_edit', array('id' => $id)));
        }

        // Not handled
        return $this->redirect($this->generateUrl('_osso2007_event_edit',array('id' => $id)));

    }
    /* =========================================================================
     * Do this in the controller for now though eventually it should maybe be in the
     * game manager?
     */
    protected function cloneGame($editData)
    {
        // Load in the game, probably don't need to do this
        $gameManager = $this->getGameManager();
        $gameId = $editData['id'];

        if ($gameId) $game = $gameManager->loadGameForId($gameId);
        else         $game = null;

        if (!$game)
        {
            // Think we can just create empty team and game teams
            die('New game not yet handled');
        }
        // Force date/time/field

        // Require at least home team be picked
        // Debug::dump($editData); die();
        $schTeamHomeId = 0;
        $schTeamAwayId = 0;
        foreach($editData['teams'] as $teamData)
        {
            if ($teamData['type'] == 'Home') $schTeamHomeId = $teamData['schTeamId'];
            if ($teamData['type'] == 'Away') $schTeamAwayId = $teamData['schTeamId'];

        }
        if (!$schTeamHomeId) return $gameId;
        $schTeamHome = $gameManager->getSchTeam($schTeamHomeId);
        if (!$schTeamHome) return $gameId;

        if ($schTeamAwayId) $schTeamAway = $gameManager->getSchTeam($schTeamAwayId);
        else                $schTeamAway = null;
        if (!$schTeamAway) $schTeamAway = $schTeamHome;

        // Create new stuff
        $gamex = $gameManager->newGame();
        $homeTeamx = $gamex->getHomeTeam();
        $awayTeamx = $gamex->getAwayTeam();

        $gamex->setProjectId($editData['projectId']);

        $gamex->setDate($editData['date']);
        $gamex->setTime($editData['time']);

        $field = $gameManager->getFieldReference($editData['fieldId']);
        $gamex->setField($field);

        $gamex->setUnitId($schTeamHome->getUnitId());

        $homeTeamx->setSchTeam($schTeamHome);
        $awayTeamx->setSchTeam($schTeamAway);

        $gameManager->persist($gamex);
      //$gameManager->persist($homeTeamx);
      //$gameManager->persist($awayTeamx);
        $gameManager->flush();
        return $gamex->getId();

        if (isset($editData['teams'])) $teams = $editData['teams'];
        else                           $teams = array();
        foreach($teams as $teamId => $teamData)
        {
            //print_r($teamId); Debug::dump($teamData); die();
            $team = $game->getGameTeamForId($teamId);
            if ($team)
            {
                $team->setTeamType($teamData['type' ]);
                $team->setScore   ($teamData['score']);

                $schTeam = $gameManager->getSchTeamReference($teamData['schTeamId']);
                $team->setSchTeam($schTeam);
            }
        }
        $gameManager->flush();

        return;
    }
    /* =========================================================================
     * Do this in the controller for now though eventually it should maybe be in the
     * game manager?
     */
    protected function updateGame($editData)
    {
        // Debug::dump($editData); die();

        // Load in the game
        $gameManager = $this->getGameManager();
        $gameId = $editData['id'];

        if ($gameId) $game = $gameManager->loadGameForId($gameId);
        else         $game = null;

        if (!$game)
        {
            // Think we can just create empty team and game teams
            die('New game not yet handled');
        }

        $game->setDate($editData['date']);
        $game->setTime($editData['time']);

        $field = $gameManager->getFieldReference($editData['fieldId']);
        $game->setField($field);

        if (isset($editData['teams'])) $teams = $editData['teams'];
        else                           $teams = array();
        foreach($teams as $teamId => $teamData)
        {
            //print_r($teamId); Debug::dump($teamData); die();
            $team = $game->getGameTeamForId($teamId);
            if ($team)
            {
                $team->setTeamType($teamData['type' ]);
                $team->setScore   ($teamData['score']);

                $schTeam = $gameManager->getSchTeamReference($teamData['schTeamId']);
                $team->setSchTeam($schTeam);
            }
        }
        $gameManager->flush();

        return;
    }
}

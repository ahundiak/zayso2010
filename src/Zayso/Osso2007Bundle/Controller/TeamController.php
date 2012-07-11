<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;
use Zayso\Osso2007Bundle\Component\HTML as BaseFormat;

class TeamController extends BaseController
{
    /* =================================================================
     * ages,genders,regions are key/description for all possible items
     *
     * searchData starts with posted check boxes
     * add to searchData and relevant game infomation
     * if any All boxes are checked then use the key/desc list to set all the values
     *
     * Kind of a mess but seems to work
     */
    public function phyTeamsListAction()
    {
        // Permissions
        $user = $this->getUser();
        if (!$user->isAdmin()) return $this->redirect($this->generateUrl('_osso2007_welcome'));

        // Pull out any saved search criteria
        $session = $this->getSession();
        $searchData = $session->get('phyTeamListSearchData');
        if (!is_array($searchData)) $searchData = array('regions' => array('R0894'));

        // Eventually come from the project
        $projectId  = $this->getProjectId();
        $ageKeys    = $this->getAgeKeys   ($projectId);
        $regionKeys = $this->getRegionKeys($projectId);
        $genderKeys = $this->getGenderKeys($projectId);

        // Process the All check
        $searchData = $this->getSearchParams($searchData,$regionKeys,$ageKeys,$genderKeys);
        $searchData['projectId'] = $projectId;

        $teamManager = $this->getTeamManager();
        $teams = $teamManager->queryPhyTeams($searchData);

        // Put together template
        $tplData = array();
        $tplData['user']   = $user;
        $tplData['format'] = new Format();
        
        $tplData['teams'] = $teams;

        $tplData['ageKeys']    = $ageKeys;
        $tplData['genderKeys'] = $genderKeys;
        $tplData['regionKeys'] = $regionKeys;
        $tplData['searchData'] = $searchData;
        
        return $this->render('Osso2007Bundle:Team:phyTeamsList.html.twig',$tplData);
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
        $projectId = $editData['projectId'];

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

        $gamex->setProjectId($projectId);
        $gamex->setNum($gameManager->getNextGameNum($projectId));
        
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

                $schTeamId = $teamData['schTeamId'];
                if ($schTeamId) $schTeam = $gameManager->getSchTeamReference($schTeamId);
                else            $schTeam = null;
                
                $team->setSchTeam($schTeam);
            }
        }
        $gameManager->flush();

        return;
    }
}

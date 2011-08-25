<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;
use Zayso\Osso2007Bundle\Component\HTML as BaseFormat;

class Format extends BaseFormat
{
    protected $searchData = array();

    public function setSearchData($searchData) { $this->searchData = $searchData; }

    public function genCheckBox($name,$data,$value)
    {
        if (is_array($value)) { $key = $value['key']; $desc = $value['desc']; }
        else                  { $key = $value;        $desc = $value; }

        if (isset($data[$key]) && $data[$key]) $checked = 'checked="checked"';
        else                                   $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="%s[%s]" value="%s" %s />',
                $desc,$name,$key,$key,$checked);
        $html = sprintf('<input type="checkbox" name="%s[%s]" value="%s" %s />%s',
                $name,$key,$key,$checked,$desc);
        return $html;
    }
    public function genAgeCheckBox($age)
    {
        if (isset($this->searchData['ages'][$age])) $checked = 'checked="checked"';
        else                                        $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="searchData[ages][%s]" value="%s" %s />',
                $age,$age,$age,$checked);
        return $html;
    }
    public function genGenderCheckBox($gender)
    {
        $genderKey = substr($gender,0,1);

        if (isset($this->searchData['genders'][$genderKey])) $checked = 'checked="checked"';
        else                                                 $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="searchData[genders][%s]" value="%s" %s />',
                $gender,$gender,$genderKey,$checked);
        return $html;
    }
    public function genRegionCheckBox($region)
    {
        if (isset($this->searchData['regions'][$region])) $checked = 'checked="checked"';
        else                                              $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="searchData[regions][%s]" value="%s" %s />',
                $region,$region,$region,$checked);
        return $html;
    }
}
class EventController extends BaseController
{
    public function editAction($id = 0)
    {
        // Permissions
        $user = $this->getUser();
        if (!$user->isAdmin()) return $this->redirect($this->generateUrl('_osso2007_welcome'));

        // Pull out any saved search criteria
        $session = $this->getSession();
        $searchData = $session->get('gameEditSearchData');
        if (!is_array($searchData)) $searchData = array();

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
      //if (isset($searchData['genders']['B']) && $searchData['genders']['B']) $searchData['genders']['C'] = 'C';
      //if (isset($searchData['genders']['C']) && $searchData['genders']['C']) $searchData['genders']['B'] = 'B';

        //Debug::dump($searchData); die();
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

        // Eventually come from the project
        $ages    = array('All','U05','U06','U07','U08','U10','U12','U14','U16','U19');
        $regions = array('All','R0160','R0498','R0557','R0991','R0894','R0914','R1174');

        $genders = array(
            array('key' => 'A', 'desc' => 'All'),
            array('key' => 'B', 'desc' => 'Boys'),
            array('key' => 'C', 'desc' => 'Coed'),
            array('key' => 'G', 'desc' => 'Girls'),
        );

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

        return $this->redirect($this->generateUrl('_osso2007_event_edit',array('id' => $id)));

    }
}

<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;
use Zayso\Osso2007Bundle\Component\HTML as BaseFormat;

class Format extends BaseFormat
{
    protected $searchData = array();

    public function setSearchData($searchData) { $this->searchData = $searchData; }

    public function genCheckBox($name,$data,$value,$desc = null)
    {
        if ($desc) { echo "$value $desc\n"; die(); }

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

        //Debug::dump($searchData); die();
        $searchData['projectId'] = $projectId;

        // For the game manager queries
        $searchx = $this->getSearchParams($searchData,$regions,$ages,$genders);
        $searchx['projectId'] = $projectId;

        // Put together template
        $tplData = array();
        $tplData['user']   = $user;
        $tplData['format'] = new Format();
        
        $tplData['game'] = $game;
        $tplData['searchData'] = $searchData;
        
        $tplData['datePickList']    = $gameManager->getDatePickList($projectId);
        $tplData['timePickList']    = $gameManager->getTimePickList($projectId);

        $tplData['fieldPickList']   = $gameManager->getFieldPickList  ($searchx);
        $tplData['schTeamPickList'] = $gameManager->getSchTeamPickList($searchx);

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

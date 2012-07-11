<?php

namespace Zayso\Osso2007Bundle\Controller;
    
use Zayso\Osso2007Bundle\Component\User;
use Zayso\Osso2007Bundle\Component\Debug;
use Zayso\Osso2007Bundle\Component\HTML as FormatHTML;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Format extends FormatHTML
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
class BaseController extends Controller
{
    protected $user = null;

    protected function getGameManager()
    {
        return $this->get('game.manager');
    }
    protected function getTeamManager()
    {
        return $this->get('team.manager');
    }
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }
    protected function getSession()
    {
        return $this->getRequest()->getSession();
    }
    protected function getProjectId()
    {
        return 70;
    }
    protected function getUser()
    {
        if ($this->user) return $this->user;

        $session = $this->getRequest()->getSession();
        $userData = $session->get('user');

        $userData['projectId'] = $this->getProjectId();

        $this->user = new User($this->container,$userData);
        return $this->user;
    }
    protected function getTplData()
    {
        $tplData = array
        (
            'user'   => $this->getUser(),
            'format' =>  new Format(),
        );
        return $tplData;
    }
    // Eventually come from the project
    protected  $ageKeys = array('All' => 'All',
            'U05' => 'U05', 'U06' => 'U06', 'U07' => 'U07',
            'U08' => 'U08', 'U10' => 'U10', 'U12' => 'U12',
            'U14' => 'U14', 'U16' => 'U16', 'U19' => 'U19');

    protected $regionKeys = array('All' => 'All',
            'R0160' => 'R0160', 'R0498' => 'R0498', 'R0557' => 'R0557',
            'R0991' => 'R0991', 'R0894' => 'R0894', 'R0914' => 'R0914',
            'R1174' => 'R1174', 'R1564' => 'R1565');

    protected $genderKeys = array(
            'All' => 'All',
            'B'   => 'Boys',
            'C'   => 'Coed',
            'G'   => 'Girls');

    protected function getAgeKeys   ($projectId = 0) { return $this->ageKeys; }
    protected function getRegionKeys($projectId = 0) { return $this->regionKeys; }
    protected function getGenderKeys($projectId = 0) { return $this->genderKeys; }

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
}
?>

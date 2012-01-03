<?php

namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\AreaBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class RefSchedController extends BaseController
{
    public function listRefSchedAction(Request $request)
    {
        $session = $request->getSession();
        $refSchedSearchData = $session->get('refSchedSearchData');

        if (!$refSchedSearchData)
        {
            $refSchedSearchData = array
            (
                'sortBy' => 1,
                $refSchedData['date1'] => '20110110',
                $refSchedData['date2'] => '20110130',
            );
        }
        
        // Grab the games
        //$gameRepo = $this->getEntityManager()->getRepository('ZaysoBundle:Game');
        //$games = $gameRepo->queryGames($refSchedData);


        // For view processor
        $this->refSchedSearchData = $refSchedSearchData;

        // Sort by
        $sortByPickList = array(1 => 'Date,Time,Field', 2 => 'Date,Field,Time', 3 => 'Date,Age,Time');

        // Dates
        $day  = new \DateInterval('P1D');
        $date = new \DateTime('01/10/2011');
        
        $dates = array();
        for($i = 0; $i < 100; $i++)
        {
            $dates[$date->format('Ymd')] = $date->format('M d, D');
            $date->add($day);
        }

        // Pass it all on
        $tplData = array();

        $tplData['games']     = array(); //$games;
        $tplData['gameCount'] = count($games);

        $tplData['ages']    = array('All','U05','U06','U07','U08','U10','U12','U14','U16','U19');
        $tplData['genders'] = array('All','Boys','Coed','Girls');
        $tplData['regions'] = array('All','R0160','R0498','R0894','R0914','R1174');
        $tplData['sortByPickList'] = $sortByPickList;
        $tplData['refSchedSearchData']   = $refSchedSearchData;
        $tplData['datesPickList']  = $dates;
        $tplData['gen']  = $this;
        return $this->render('AreaBundle:Schedule:schedule.html.twig',$tplData);
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

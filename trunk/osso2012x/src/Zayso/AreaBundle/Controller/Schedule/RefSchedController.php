<?php

namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\AreaBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Format\HTML as FormatHTML;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\EventPerson;

class RefSchedController extends BaseController
{
    public function assignAction(Request $request, $id = 0, $pos = null)
    {
        $gameManager = $this->getGameManager();
        $game = $gameManager->loadEventForId($id);
        if (!$game)
        {
            return $this->redirect($this->generateUrl('zayso_area_schedule_referee_list'));
        }
        // Just for grins
        $eventPersonId = 0;
        $types = array(EventPerson::Type4th,EventPerson::TypeObs);
        foreach($types as $type)
        {
            $eventPerson = $game->getPersonForType($type);
            if (!$eventPerson)
            {
                $eventPerson = new EventPerson();
                $eventPerson->setId(--$eventPersonId);
                $eventPerson->setType($type);
                $eventPerson->setEvent($game);
                $game->addPerson($eventPerson);
            }
        }
        // Make life a bit easier by always having a person?
        foreach($game->getPersons() as $eventPerson)
        {
            if (!$eventPerson->getPerson())
            {
                $person = new Person();
                $eventPerson->setPerson($person);
            }
        }
        // Gather up data
        $tplData = array();

        $tplData['game'] = $game;
        $tplData['pos']  = $pos;

        $tplData['gen']    = $this;
        $tplData['format'] = new FormatHTML();

        return $this->render('ZaysoAreaBundle:Schedule:assign.html.twig',$tplData);
    }
    public function listAction(Request $request)
    {
        $session = $request->getSession();
        $refSchedSearchData = $session->get('refSchedSearchData');

        if (!$refSchedSearchData)
        {
            $refSchedSearchData = array
            (
                'sortBy' => 1,
                'date1'  => '20110110',
                'date2'  => '20110120',
            );
        }
        
        // Grab the games
        //$gameRepo = $this->getEntityManager()->getRepository('ZaysoBundle:Game');
        $games = $this->getGameManager()->queryGames($refSchedSearchData);

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

        $tplData['games']     = $games;
        $tplData['gameCount'] = count($games);

        $tplData['ages']    = array('All','U05','U06','U07','U08','U10','U12','U14','U16','U19');
        $tplData['genders'] = array('All','Boys','Coed','Girls');
        $tplData['regions'] = array('All','R0160','R0498','R0894','R0914','R1174');
        $tplData['sortByPickList']      = $sortByPickList;
        $tplData['refSchedSearchData']  = $refSchedSearchData;
        $tplData['datesPickList']       = $dates;
        $tplData['gen']  = $this;

        $tplData['format'] = new FormatHTML();
        
        return $this->render('ZaysoAreaBundle:Schedule:referee.html.twig',$tplData);
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
    public function genTeam($eventTeam)
    {
        if (!$eventTeam) return '';

        // Real team
        $team = $eventTeam->getTeam();
        if (!$team) return '';

        $key = $team->getTeamKeyExpanded();
        if ($key) return $key;

        $key = $team->getTeamKey();
        if (!$key) return '';

      //$key = sprintf('%s-%s-%s %s',substr($key,0,5),substr($key,5,4),substr($key,9,2),substr($key,12));
        return $key;
    }
    public function genReferees($game)
    {
        $refs = $game->getPersons();
        
        $html = '<table>';
        foreach($refs as $ref)
        {
            $type = $ref->getType();

            $person = $ref->getPerson();
            if ($person) $name = $person->getPersonName();
            else         $name = 'Unassigned';

            $url = $this->generateUrl('zayso_area_schedule_referee_assign',array('id' => $game->getId(),'pos' => $type));

            $row = sprintf('<tr><td><a href="%s">%s:</a></td><td>%s</td></tr>',$url,$type,$name);
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

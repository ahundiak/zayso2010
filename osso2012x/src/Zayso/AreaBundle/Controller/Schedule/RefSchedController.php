<?php

namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\AreaBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Format\HTML as FormatHTML;
use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\EventPerson;

class RefSchedGameViewHelper
{
    protected $format = null;
    protected $game   = null;

    public function __construct($format)
    {
        $this->format = $format;
    }
    public function setGame($game)
    {
        $this->game = $game;
    }
    public function date()
    {
        return $this->format->date($this->game->getDate());
    }
    public function time()
    {
        return $this->format->time($this->game->getTime());
    }
    public function field()
    {
        $field = $this->game->getField();
        if ($field) return $field->getKey();
        return null;
    }
    public function homeTeam()
    {
        return $this->genTeam($this->game->getHomeTeam());
    }
    public function awayTeam()
    {
        return $this->genTeam($this->game->getAwayTeam());
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
    public function officials()
    {
        $game = $this->game;

        $refs = $game->getEventPersonsSorted();

        $html = '<table>';
        foreach($refs as $ref)
        {
            $type = $ref->getType();

            $person = $ref->getPerson();
            if ($person) $name = $person->getPersonName();
            else         $name = '';

            // Be nice to make this conditional, only show links if the account can sign up
            $url = $this->format->generateUrl('zayso_area_schedule_referee_assign',array('id' => $game->getId(),'pos' => $type));

            $row = sprintf('<tr><td><a href="%s">%s:</a></td><td>%s</td></tr>',$url,$type,$name);
            $html .= $row;
        }
        $html .= '</table>';
        return $html;
    }
    protected $lastGameDate = null;
    public function genGameDateBreak($game)
    {
        $game = $this->game;

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
    /* ===========================================================
     * Event person specific stuff
     */
    public function genEventPersonName($eventPerson)
    {
        $officialsPickList = $this->officialsPickList;

        // See if official can be changed
        $person = $eventPerson->getPerson();
        if ($person)
        {
            $personId = $person->getId();
            if (!isset($officialsPickList[$personId]))
            {
                return $this->format->escape($person->getPersonName());
            }
            $officialsPickList[0] = 'Remove';
        }
        else
        {
            $officialsPickList[0] = 'Vacant';
            $personId = 0;
        }

        $html  = sprintf('<select name="eventPerson[%s][personIdNew]"  >',$eventPerson->getId());
        $html .= $this->format->formOptions($officialsPickList,$personId);
        $html .= '</select>' . "\n";

        return $html;
    }
}
class RefSchedController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_area.game.schedule.manager');
    }
    protected function getFormatHTML()
    {
        return $this->get('zayso_core.format.html');
    }
    protected function getGameViewHelper()
    {
        $format = $this->get('zayso_core.format.html');
        return new RefSchedGameViewHelper($format);
    }
    protected function getSearchViewHelper()
    {
        $format = $this->get('zayso_core.format.html');
        return new RefSchedSearchViewHelper($format);
    }
    protected function processEventPersonPosted($event,$eventPersonPosted)
    {
        $manager = $this->getScheduleManager();

        // If there is no new person then there can be no change
        if (!isset($eventPersonPosted['personIdNew'])) return;

        // If there was no change then ignore, move this downward to eliminate need for personIdOld
        // if ($eventPersonPosted['personId'] == $eventPersonPosted['personIdNew']) return;

        // Is it an existing record?
        foreach($event->getEventPersons() as $eventPerson)
        {
            if ($eventPerson->getId() == $eventPersonPosted['id'])
            {
                $personId = $eventPersonPosted['personIdNew'];

                $person = $eventPerson->getPerson();
                if ($person)
                {
                    // No need to update if they are the same
                    if ($person->getId() == $personId) return;
                }
                if ($personId) $person = $manager->getPersonReference($personId);
                else           $person = null;

                // Delete if no person and not protected
                if (!$eventPerson->isProtected() && !$person)
                {
                    $manager->remove($eventPerson);
                    $manager->flush();
                    return;
                }
                $eventPerson->setPerson($person);
                $manager->flush();
                return;
            }
        }
        // Not existing but did change, should mean to insert new record
        $personId = $eventPersonPosted['personIdNew'];

        // Don't think this should really happen
        if (!$personId) return;

        // Neither should this?
        $eventPersonId = $eventPersonPosted['id'];
        if ($eventPersonId > 0) return;

        // Or this
        $type = $eventPersonPosted['type'];
        if (!$type) return;

        // Go for it
        $eventPerson = new EventPerson();
        $eventPerson->setType($type);
        $eventPerson->setEvent($event);
        $eventPerson->setPerson($manager->getPersonReference($personId));
        $event->addPerson($eventPerson);
        $manager->persist($eventPerson);
        $manager->flush();
        
    }
    public function assignPostAction(Request $request)
    {
        // Make sure have a valid game id
        $manager = $this->getScheduleManager();
        $gameId = $request->request->get('gameId');
        $game = $manager->loadEventForId($gameId);
        if (!$game)
        {
            return $this->redirect($this->generateUrl('zayso_area_schedule_referee_list'));
        }
        // Grab and process the event persons
        $eventPersonsPosted = $request->request->get('eventPerson');
        foreach($eventPersonsPosted as $eventPersonPosted)
        {
            $this->processEventPersonPosted($game,$eventPersonPosted);
        }
        // And redirect
        return $this->redirect($this->generateUrl('zayso_area_schedule_referee_assign',array('id' => $gameId)));
    }
    public function assignAction(Request $request, $id = 0, $pos = null)
    {
        if ($request->getMethod() == 'POST') return $this->assignPostAction($request);

        $manager = $this->getScheduleManager();
        $game = $manager->loadEventForId($id);
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
                //$eventPerson->setPerson($person);
            }
        }
        // Need a pick list for the account referees
        $user = $this->getUser();
        $accountId = $user->getAccountId();
        $officials = $manager->getOfficialsForAccount(0,$accountId);

        $officialsPickList = array(0 => 'Unassigned');
        foreach($officials as $official)
        {
            $officialsPickList[$official->getId()] = $official->getPersonName();
        }

        // Try to set user to default position
        if ($pos && count($officials))
        {
            $eventPerson = $game->getPersonForType($pos);
            if ($eventPerson && !$eventPerson->getPerson())
            {
                // Getting way to clever here
                $officialId = $officials[0]->getId();
                $officialsPickList[$officialId] = $officialsPickList[$officialId] . '*';
                $eventPerson->setPerson($officials[0]);
            }
        }
        // Not completely sure about this
        $gameView = $this->getGameViewHelper();
        $gameView->setGame($game);
        $gameView->officialsPickList = $officialsPickList;
        
        // Gather up data
        $tplData = array();

        $tplData['gameView'] = $gameView;
        $tplData['game']     = $game; // Keep for now for event persons
        $tplData['pos']      = $pos;

        $tplData['gen']    = $this;
        $tplData['format'] = $this->format = $this->getFormatHTML();

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
                'time1'  => '0600',
                'time2'  => '2100',
            );
        }
        
        // Grab the games
        //$gameRepo = $this->getEntityManager()->getRepository('ZaysoBundle:Game');
        $games = $this->getScheduleManager()->queryGames($refSchedSearchData);

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
        $tplData['gameView']  = $this->getGameViewHelper();
        $tplData['gameCount'] = count($games);

        $tplData['ages']    = array('All','U05','U06','U07','U08','U10','U12','U14','U16','U19');
        $tplData['genders'] = array('All','Boys','Coed','Girls');
        $tplData['regions'] = array('All','R0160','R0498','R0894','R0914','R1174');
        $tplData['sortByPickList']      = $sortByPickList;
        $tplData['refSchedSearchData']  = $refSchedSearchData;
        $tplData['datesPickList']       = $dates;

        $tplData['searchView'] = $this->getSearchViewHelper();

        // Need for search form helper
        $tplData['gen']  = $this;

        // Not sure but eventually remove
        $tplData['format'] = $this->format = $this->getFormatHTML();
        
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
}
class RefSchedSearchViewHelper
{
    protected $format = null;

    static public $yearPickList = array(
        '2016' => '2016', '2015' => '2015', '2014' => '2014', '2013' => '2013',
        '2012' => '2012', '2011' => '2011', '2010' => '2010', '2009' => '2009',
        '2008' => '2008', '2007' => '2007', '2006' => '2006', '2005' => '2005',
        '2004' => '2004', '2003' => '2003', '2002' => '2002', '2001' => '2001',
    );
    static public $monthPickList = array(
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
        '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
        '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
    );
    static public $dayPickList = array(
        '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06',
        '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12',
        '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18',
        '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24',
        '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30',
        '31' => '31',
    );
    static public $hourPickList = array(
        '06' => '06 AM', '07' => '07 AM', '08' => '08 AM', '09' => '09 AM',
        '10' => '10 AM', '11' => '11 AM', '12' => '12 PM', '13' => '01 PM',
        '14' => '02 PM', '15' => '03 PM', '16' => '04 PM', '17' => '05 PM',
        '18' => '06 PM', '19' => '07 PM', '20' => '08 PM', '21' => '09 PM',
    );
    public function __construct($format)
    {
        $this->format = $format;
    }
    public function genDate($name,$date)
    {
        $html = null;

        $html .= sprintf('<select name="%s[year]" >',$name);
        $html .= $this->format->formOptions(self::$yearPickList,substr($date,0,4));
        $html .= '</select>' . "\n";

        $html .= sprintf('<select name="%s[month]" >',$name);
        $html .= $this->format->formOptions(self::$monthPickList,substr($date,4,2));
        $html .= '</select>' . "\n";

        $html .= sprintf('<select name="%s[day]" >',$name);
        $html .= $this->format->formOptions(self::$dayPickList,substr($date,6,2));
        $html .= '</select>' . "\n";

        return $html;
    }
    public function genHour($name,$time)
    {
        $html = null;

        $html .= sprintf('<select name="%s[hour]" >',$name);
        $html .= $this->format->formOptions(self::$hourPickList,substr($time,0,2));
        $html .= '</select>' . "\n";

        return $html;
    }
    public function genDateDesc($date)
    {
        $date = sprintf('%s/%s/%s',substr($date,0,4),substr($date,4,2),substr($date,6,2));
        $date = new \DateTime($date);

        return $date->format('M d, D');
    }
}

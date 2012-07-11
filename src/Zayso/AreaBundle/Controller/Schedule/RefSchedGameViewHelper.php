<?php
namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;

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
    /* ===========================================================
     * True if the date has changed
     */
    protected $lastGameDate = null;
    public function genGameDateBreak()
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

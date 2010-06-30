<?php


class EventViewItem extends Proj_View_Item
{
    function isBlank()
    {
        if ($this->item) return FALSE;
        return TRUE;
    }
}
class RegionScheduleExport
{
    protected $context = NULL;
    
    public function __construct($context)
    {
        $this->context = $context;
    }
    public function escape($value)
    {
        return $value;
    }
    public function formatDateIso($date)
    {
        // 2005-09-06T00:00:00.000
        $year  = substr($date,0,4);
        $month = substr($date,4,2);
        $day   = substr($date,6,2);
        
        return "{$year}-{$month}-{$day}T00:00:00.000";
    }
    public function formatTimeIso($time)
    {
        // 1899-12-31T17:30:00.000
        $hour   = substr($time,0,2);
        $minute = substr($time,2,2);

        return "1899-12-31T{$hour}:{$minute}:00.000";
    }
    public function formatDateTimeIso($date,$time)
    {
        // 2007-07-20T09:00:00.000
        $year   = substr($date,0,4);
        $month  = substr($date,4,2);
        $day    = substr($date,6,2);
        $hour   = substr($time,0,2);
        $minute = substr($time,2,2);
        
        return "{$year}-{$month}-{$day}T{$hour}:{$minute}:00.000";
    }
    public function formatDateDow($date)
    {   
        if (strlen($date) < 8) return $date;
        
        $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
        
        return date('l',$stamp);
    }
    public function formatTeam($team)
    {
        $desc = $team->schedDesc;
        
        $phone = $team->coachHead->phoneHome->number;
        //if ($phone) $desc .= ' ' . $phone;
        
        return $this->escape($desc);
            
    }
    public function export2008Fall($xmlFileName,$unitId = 1)
    {
        $models = $this->context->models;
        $phyTeamModel - $models->PhyTeamModel;
        
        // List of teams
        $search = new SearchData();
        $search->wantx = TRUE;
        $search->wantCoachHead = TRUE;
        
        $search->yearId = 8;
        $search->seasonTypeId = SeasonTypeModel::TYPE_FALL;
        $search->divisionId   = $models->DivisionModel->getDivisionIdsForAgeRange(10,19,TRUE,TRUE,TRUE);
     
        $this->teams = $teams = $models->PhyTeamModel->search($search);
/*
        foreach($teams as $team) {
        Zend_Debug::dump($team); die();
        }*/
        
        // Event ids
        $search = new SearchData();

        /* Date range */
        $search->dateGE = '20080801';
        $search->dateLE = '20081130';
        $search->unitId = $unitId;
        $search->eventTypeId = $models->EventType->TYPE_GAME;
        $search->divisionId  = $models->DivisionModel->getDivisionIdsForAgeRange(10,19,TRUE,TRUE,TRUE);
        
        $search->eventTeamTypeId = array($models->EventTeamType->TYPE_HOME,$models->EventTeamType->TYPE_AWAY);
        
        $eventIds = $models->EventTeam->searchDistinct($search);
        
        /* Now the full query */
        $search = new SearchData();
        $search->eventId = $eventIds;
        $search->orderBy = 1;
        
        $this->events = $events = $models->EventModel->searchSchedule($search);
        
        // Break up by division
        $eventDivs['U10B'] = array();
        $eventDivs['U10G'] = array();
        $eventDivs['U12B'] = array();
        $eventDivs['U12G'] = array();
        $eventDivs['U14B'] = array();
        $eventDivs['U14G'] = array();
        $eventDivs['U16B'] = array();
        $eventDivs['U16G'] = array();
        $eventDivs['U19B'] = array();
        $eventDivs['U19G'] = array();
        
        foreach($events as $event) {
            $team = $event->teamHome;
            if ($team) {
                $divDesc = $team->divisionDesc;
                $divDesc = str_replace('C','B',$divDesc);
                if (!isset($eventDivs[$divDesc][$event->id])) 
                    $eventDivs[$divDesc][$event->id] = new EventViewItem($this,$event);
            }
            $team = $event->teamAway;
            if ($team) {
                $divDesc = $team->divisionDesc;
                $divDesc = str_replace('C','B',$divDesc);
                if (!isset($eventDivs[$divDesc][$event->id])) 
                    $eventDivs[$divDesc][$event->id] = new EventViewItem($this,$event);
            }
        }
        foreach($eventDivs as $key => $eventDiv) {
            $eventDivs[$key] = $this->addBlankRows($eventDiv);
        }
        // And store
        $this->eventDivs = $eventDivs; // Zend_Debug::dump($eventDivs); die();
            
        // Render
        ob_start();
        include 'exports/RegionScheduleTpl.php';
        $out = ob_get_clean();
       
        // Save
        if (!$xmlFileName) return $out;
        
        $file = fopen($xmlFileName,'wt');
        fwrite($file,$out);
        fclose($file);        
    }
    function addBlankRows($rowsx)
    {
        $rows = array();
        $date = NULL;
        foreach($rowsx as $row) {
            if ($date && ($date != $row->date)) $rows[] = new EventViewItem($this,NULL);
            $rows[] = $row;
            $date = $row->date;
        }
        return $rows;
    }
}
?>

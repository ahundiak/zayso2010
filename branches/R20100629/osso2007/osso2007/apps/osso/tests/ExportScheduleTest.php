<?php
class EventViewItem extends Proj_View_Item
{
    function isBlank()
    {
        if ($this->item) return FALSE;
        return TRUE;
    }
}
class ExportScheduleTest extends BaseAppTest
{
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
        if ($phone) $desc .= ' ' . $phone;
        
        return $this->escape($desc);
            
    }
    public function testSchedule2006Fall()
    {
        $models = $this->context->models;
        
        // List of teams
        $search = new SearchData();
        $search->wantx = TRUE;
        $search->wantCoachHead = TRUE;
        
        $search->yearId = 6;
        $search->seasonTypeId = SeasonTypeModel::TYPE_FALL;
        $search->divisionId   = $models->DivisionModel->getDivisionIdsForAgeRange(10,19,TRUE,TRUE,TRUE);
        
        $this->teams = $teams = $models->PhyTeamModel->search($search);
        $this->assertEquals(count($teams),179);
        
        // Event ids
        $search = new SearchData();

        /* Date range */
        $search->dateGE = '20060801';
        $search->dateLE = '20061130';
        $search->unitId = 1;
        $search->eventTypeId = $models->EventType->TYPE_GAME;
        $search->divisionId  = $models->DivisionModel->getDivisionIdsForAgeRange(10,19,TRUE,TRUE,TRUE);
        
        $search->eventTeamTypeId = array($models->EventTeamType->TYPE_HOME,$models->EventTeamType->TYPE_AWAY);
        
        $eventIds = $models->EventTeam->searchDistinct($search);
        
        /* Now the full query */
        $search = new SearchData();
        $search->eventId = $eventIds;
        
        $this->events = $events = $models->EventModel->searchSchedule($search);
        
        // Break up by division
        $eventDivs['U10G'] = array();
        $eventDivs['U10B'] = array();
        $eventDivs['U12G'] = array();
        $eventDivs['U12B'] = array();
        $eventDivs['U14G'] = array();
        $eventDivs['U14B'] = array();
        $eventDivs['U16G'] = array();
        $eventDivs['U16B'] = array();
        $eventDivs['U19C'] = array();
        
        foreach($events as $event) { 
            $eventDivs[$event->divisionDesc][$event->id] = new EventViewItem($this,$event);
        }
        foreach($eventDivs as $key => $eventDiv) {
            $eventDivs[$key] = $this->addBlankRows($eventDiv);
        }
        // And store
        $this->eventDivs = $eventDivs; // Zend_Debug::dump($eventDivs); die();
            
        // Render it
        $file = fopen('Schedule2006Fall.xml','wt');
        $this->assertNotNull($file);
       
        ob_start();
        include 'ScheduleTpl.php';
        $out = ob_get_clean();
       
        fwrite($file,$out);
        fclose($file);        
    }
    public function sestExportSchedule()
    {
        $models = $this->context->models;
        
        $search = new SearchData();

        /* Date range */
        $search->dateGE = '20070301';
        $search->dateLE = '20070630';
        $search->unitId = 1;
        $search->eventTypeId = $models->EventType->TYPE_GAME;
        
        $search->eventTeamTypeId = array($models->EventTeamType->TYPE_HOME,$models->EventTeamType->TYPE_AWAY);
        
        $eventIds = $models->EventTeam->searchDistinct($search);
        
        /* Now the full query */
        $search = new SearchData();
        $search->eventId = $eventIds;
        
        $this->events = $events = $models->EventModel->searchSchedule($search);
        
        // Break up by division
        $eventDivs['U10C'] = array();
        $eventDivs['U12C'] = array();
        $eventDivs['U14C'] = array();
        foreach($events as $event) { 
            $eventDivs[$event->divisionDesc][] = new EventViewItem($this,$event);
        }
        foreach($eventDivs as $key => $eventDiv) {
            $eventDivs[$key] = $this->addBlankRows($eventDiv);
        }
        // And store
        $this->eventDivs = $eventDivs; // Zend_Debug::dump($eventDivs); die();
        
        $file = fopen('DivSched2007.xml','wt');
        $this->assertNotNull($file);
        ob_start();
//      include 'DivSchedListgkTplx.php';
        include 'DivSchedListjaTplx.php';
        $out = ob_get_clean();
        fwrite($file,$out);
        fclose($file);
        
        //Zend_Debug::dump($events);
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

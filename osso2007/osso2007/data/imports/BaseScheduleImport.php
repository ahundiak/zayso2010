<?php
class Game
{
}
class BaseScheduleImport
{
    // Global context
    protected $context = NULL;
    
    // Current work sheet name
    protected $workSheetName = NULL;
    
    // Indicates if the database should actually be updated
    public $update = TRUE;
    
    // Cache of schedule teams
    protected $teams = array();
    
    // To handle different spellings
    protected $fieldAliases = array(
        'International'      => 'Palmer Int',
        'JH3'                => 'John Hunt 3',
        'JH4'                => 'John Hunt 4',
        'JH5'                => 'John Hunt 5',
        'JH7'                => 'John Hunt 7',
        'JH8'                => 'John Hunt 8',
    	'John Hunt #4'       => 'John Hunt 4',
    	'John Hunt #7'       => 'John Hunt 7',
        'Harvest Elementary' => 'Harvest',
        'Westminster East'   => 'Westmin U10 East',
        'WCA U10'            => 'Westmin U10 East',
        'WCA U12'            => 'Westmin U12 West',
        'Madison'            => 'Madison TBD',
        'Lincoln'            => 'South Lincoln',
        'Lincoln CO'         => 'South Lincoln',
        'Dublin 1.'          => 'Dublin #1',
        'Dublin 5.'          => 'Dublin #5',
        'Dublin 1'           => 'Dublin #1',
        'Dublin 2'           => 'Dublin #2',
        'Dublin 3'           => 'Dublin #3',
        'Dublin 4'           => 'Dublin #4',
        'Dublin 5'           => 'Dublin #5',
        'NE Madison CO'      => 'HG Park U10-1',
        'SJU10-1'            => 'SJ U10-1',
        'South Lincoln / D'  => 'Davidson D',
        'Camp Hellen'        => 'Camp Helen',
        'Camp Hellen U10'    => 'Camp Helen U10',
        'HGHS'               => 'Hazel Green HS',
        'Hazelgreen High'    => 'Hazel Green HS',
        'Monrovia Middle School'  => 'Monrovia Middle',
        'Athens Sportsplex Fld 3' => 'Athens 3',
        'Athens Sportsplex Fld 4' => 'Athens 4',
        'Cullman 1'          => 'Cullman #1',
    );
    
    // Defaults
    protected $yearId         = 8;
    protected $seasonTypeId   = 1;
    protected $scheduleTypeId = 1;
    protected $eventTypeId    = 1;
    protected $eventPoint1    = 1;
    protected $eventPoint2    = 1;
    
    function __construct($context)
    {
        $this->context = $context;
    }
    function getSchTeam($unitId,$divId,$teamSeqNum)
    {
        $models = $this->context->models;
        
        if (!$unitId || !$divId) return NULL;

        // Grab the seq number
        if ($teamSeqNum < 1) {
            $schTeam = $models->SchTeamModel->find(0);
            $schTeam->id         = 0;
            $schTeam->yearId     = $this->yearId;
            $schTeam->unitId     = $unitId;
            $schTeam->divisionId = $divId;
            return $schTeam; // Basically we know the unit and division but not the exact team
            //die("teamSeqNumber is 0 $team\n");
        }
        // Check the cache
        if ( isset($this->teams[$unitId][$divId][$teamSeqNum])) 
            return $this->teams[$unitId][$divId][$teamSeqNum];
        
        // All the teams for the division
        $search = new SearchData();
        $search->wantx         = TRUE;
        $search->wantPhyTeam   = TRUE;
        $search->wantCoachHead = TRUE;

        $search->divisionId     = $divId;
        $search->unitId         = $unitId;
        $search->yearId         = $this->yearId;
        $search->seasonTypeId   = $this->seasonTypeId;
        $search->scheduleTypeId = $this->scheduleTypeId;
        
        $items = $models->SchTeamModel->search($search);
        foreach($items as $item) {
            $this->teams[$unitId][$divId][$item->phyTeam->divisionSeqNum] = $item;
        }
        // Make sure we got it
        if ( isset($this->teams[$unitId][$divId][$teamSeqNum])) 
            return $this->teams[$unitId][$divId][$teamSeqNum];
        
        die("Invalid schedule team: U=$unitId D=$divId SN=$teamSeqNum\n");
    }
    public function getFieldByKey($key)
    {
        if (isset($this->fieldAliases[$key])) $key = $this->fieldAliases[$key];
        
        if ($key == 'TBD') {
            $field =  $this->context->models->FieldModel->find(0);
            $field->id = 0;
            return $field;
        }
        $field = $this->context->models->FieldModel->searchByKey($key);
        if (!$field) {
            // if (1) return NULL;
            die("Invalid field key $key");
        }
        return $field;
    }
    public function createEvent($event,$homeSchTeam,$awaySchTeam)
    {
        $models = $this->context->models;
        
        // Need at least one team
        if (!$homeSchTeam && !$awaySchTeam) die("Missing both schedule teams");
        
        // Tweak the time, really a hack
        if (!$homeSchTeam) $event->time = 'BN';
        if (!$awaySchTeam) $event->time = 'BN';
        
        // Already have one?
        $eventx = $models->EventModel->searchByDateTimeField($event->date,$event->time,$event->fieldId);
        if ($eventx) {
            // echo "Existing {$eventx->id} {$eventx->date} {$eventx->time} {$eventx->fieldId}\n";
            
            // Check that teams have not changed
            if ($homeSchTeam && ($homeSchTeam->id != $eventx->teamHome->schTeamId)) {
                echo "Home Team Mismatch\n";
                Zend_Debug::dump($homeSchTeam);
                Zend_Debug::dump($eventx);
                die();
            }
            if ($awaySchTeam && ($awaySchTeam->id != $eventx->teamAway->schTeamId)) {
                echo "Away Team Mismatch\n";
                Zend_Debug::dump($eventx);
                die();
            }
            return NULL;
        }
        echo "New {$event->date} {$event->time} {$event->fieldId}\n";
        if (!$this->update) {
            $this->gameCnt++;
            return;
        }       
        // Make the event
        $event->yearId         = $this->yearId;
        $event->seasonTypeId   = $this->seasonTypeId;
        $event->scheduleTypeId = $this->scheduleTypeId;
        $event->eventTypeId    = $this->eventTypeId;
        $event->point1         = $this->eventPoint1;
        $event->point2         = $this->eventPoint2;
        
        $event->unitId         = NULL;
        $event->status         = NULL;
        $event->duration       = 0;
        
        
        $eventId = $models->EventModel->save($event);
        if (!$eventId) die('Event not created');
        
        // Do the teams
        $eventTeam = $models->EventTeamModel->find(0);
        $eventTeam->eventId = $eventId;
        $eventTeam->score   = 0;
        
        if ($homeSchTeam) {
            $eventTeam->schTeamId  = $homeSchTeam->id;
            $eventTeam->eventTeamTypeId = EventTeamTypeModel::TYPE_HOME;
            $eventTeam->yearId     = $homeSchTeam->yearId;
            $eventTeam->unitId     = $homeSchTeam->unitId;
            $eventTeam->divisionId = $homeSchTeam->divisionId;
            $eventTeamId = $models->EventTeamModel->save($eventTeam);
            if (!$eventTeamId) die('home event team not created');   
        }
        if ($awaySchTeam) {
            $eventTeam->schTeamId  = $awaySchTeam->id;
            $eventTeam->eventTeamTypeId = EventTeamTypeModel::TYPE_AWAY;
            $eventTeam->yearId     = $awaySchTeam->yearId;
            $eventTeam->unitId     = $awaySchTeam->unitId;
            $eventTeam->divisionId = $awaySchTeam->divisionId;
            $eventTeamId = $models->EventTeamModel->save($eventTeam);
            if (!$eventTeamId) die('away event team not created');   
        }
        $this->gameCnt++;
        
        // All done
        return $eventId;
    }
    function import($xmlFileName)
    {
        $this->gameCnt = 0;
        
        $xmlReader = new XMLReader();
        $flag = $xmlReader->open($xmlFileName);
        if (!$flag) die("Unable to open $xmlFileName\n");
        
        while($xmlReader->read()) 
        {
            if ($xmlReader->nodeType == XMLReader::ELEMENT) {
                
                // Work sheet name <Worksheet ss:Name="Sheet1">
                if ($xmlReader->name == 'Worksheet') {
                    $this->workSheetName  = $xmlReader->getAttribute('ss:Name');
                    //die("Work sheet name {$this->workSheetName}");
                }
                // Assume one game per row
                if ($xmlReader->name == 'Row') {
                    $rowNode = $xmlReader->expand();
                    $cellNodes = $rowNode->getElementsByTagName('Cell');
                    $this->importRow($cellNodes);
                }
            }
        }
        $xmlReader->close();
        
        return $this->gameCnt;
        
    }
}
?>

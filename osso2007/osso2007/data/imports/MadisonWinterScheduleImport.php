<?php

class MadisonWinterScheduleImport extends BaseScheduleImport
{
    function getSchTeamByKey($age,$seq)
    {
        $models = $this->context->models;
        
        // Filter by age
        $ages = array(
            'U6C'  => 'U06C',
            'U8C'  => 'U08C',
            'U10C' => 'U10C',
            'U12C' => 'U12C',
            'U14C' => 'U14C',
        );
        if (!isset($ages[$age])) return NULL;

        // Grab the division
        $divKey = $ages[$age];
        $divId  = $models->DivisionModel->getDivisionIdForKey($divKey);

        if (!$divId) die("Invalid age division $age\n");

        // Grab the seq number
        $teamSeqNum = (int)substr($seq,1);
        
        // Use common routine
        $unitId = 4;
        return $this->getSchTeam($unitId,$divId,$teamSeqNum);

    }
    function importRow($cellNodes)
    {
        if (!isset($this->workSheetNames[$this->workSheetName])) return;
        
        $game = new Game();
        $models = $this->context->models;
     
        // Extract game info
        for ($cellIndex = 0; $cellIndex < $cellNodes->length; $cellIndex++) {    
            $cellNode = $cellNodes->item($cellIndex);
            $dataNodes = $cellNode->getElementsByTagName('Data');
            $dataNode = $dataNodes->item(0);
            if ($dataNode) {
                $dataNodeValue = $dataNode->nodeValue;
                switch ($cellIndex) {
                    case 0:
                        $ssType = $dataNode->getAttribute('ss:Type'); // Needed to add ss prefix
                        //echo "ssType $ssType $dataNodeValue\n";
                        if ($ssType == 'DateTime') {
                            $game->date = $dataNodeValue;
                        }   
                        break;
                        
                    case 1: $game->time  = $dataNodeValue; break;
                    case 2: $game->age   = $dataNodeValue; break;
                    case 3: $game->away  = $dataNodeValue; break;
                    case 4: $game->home  = $dataNodeValue; break;
                    case 5: $game->field = $dataNodeValue; break;
                    
                }
            }
        }

        // Make sure it a game row
        if ((!isset($game->date)) || (!$game->date)) return;
        if ((!isset($game->time)) || ($game->time == 'NO GAMES')) return;

        // Start the event stuff
        $event = $models->EventModel->find(0);
        $event->yearId         = $this->yearId;
        $event->seasonTypeId   = $this->seasonTypeId;
        $event->scheduleTypeId = $this->scheduleTypeId;
        $event->eventTypeId    = $this->eventTypeId;
        $event->unitId         = NULL;
        $event->status         = NULL;
        $event->duration       = 0;
        $event->fieldId        = 0;
        
        // Date
        $event->date = $models->DateTimeModel->getDateFromExcelFormat($game->date);
        if (strlen($event->date) != 8) {
            die("Invalid date {$game->date}\n");
        }
        if ($event->date > $this->datex) return;
        
        // Time
        $event->time = $models->DateTimeModel->getTimeFromExcelFormat($game->time);
        if (strlen($event->time) != 4) {
            die("Invalid time {$game->time}\n");
        }
               
        // Grab the field
        $field = $this->getFieldByKey($game->field);
        if ($field) $event->fieldId = $field->id;
       
        // Grab the teams
        $homeSchTeam = $this->getSchTeamByKey($game->age,$game->home);
        $awaySchTeam = $this->getSchTeamByKey($game->age,$game->away);

        if (!$homeSchTeam || !$awaySchTeam) return;
                
        // Make it
        $this->createEvent($event,$homeSchTeam,$awaySchTeam);
    }
}
?>

<?php

class HazelGreenScheduleImport extends BaseScheduleImport
{
    function getSchTeamByKey($team)
    {
        $models = $this->context->models;
        
        if (!$team) return NULL;
        
        $temp = explode('-',$team);
        if (count($temp) < 2) die("Invalid team Key $team\n");
        
        // Grab the unit
        $unitKey = $temp[0];
        switch($unitKey) {
            case 'Cullman': $unitKey = 'R0414'; break;
            case 'Athens':  $unitKey = 'R0916'; break;
            case 'Ardmore': $unitKey = 'R9992'; break;
        }
        $unit = $models->UnitModel->searchByKey($unitKey);
        if (!$unit) die("Invalid unit key $team\n");
        
        // Grab the division
        $divKey = substr($temp[1],0,4);
        $divId  = $models->DivisionModel->getDivisionIdForKey($divKey);
        if (!$divId) die("Invalid age division $team\n");

        // Grab the seq number
        if (count($temp) > 2) $teamSeqNum = (int)$temp[2];
        else                  $teamSeqNum = 0;
        
        // Use common routine
        return $this->getSchTeam($unit->id,$divId,$teamSeqNum);

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
                    case 2:
                        $ssType = $dataNode->getAttribute('Type');
                        if ($ssType == 'DateTime') {
                            $game->date = $dataNodeValue;
                        }   
                        break;
                        
                    case 10: $game->time  = $dataNodeValue; break;
                    case  9: $game->field = $dataNodeValue; break;
                    
                    case  4: $game->awayCoach = $dataNodeValue; break;
                    case  5: $game->awayPhone = $dataNodeValue; break;
                    case  6: $game->awayTeam  = $dataNodeValue; break;
                    case 11: $game->homeCoach = $dataNodeValue; break;
                    case 12: $game->homePhone = $dataNodeValue; break;
                    case  8: $game->homeTeam  = $dataNodeValue; break;
                }
            }
        }
        // Make sure it a game row
        if ((!isset($game->date)) || (!$game->date)) return;
        if ((!isset($game->time)) || ($game->time == 'NO GAMES')) return;

//        if ($game->date > '2007-09-08') return;
//        if ($game->date != '20070818') return;
        
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
        $homeSchTeam = $this->getSchTeamByKey($game->homeTeam);
        $awaySchTeam = $this->getSchTeamByKey($game->awayTeam);
        
        // Make it
        $this->createEvent($event,$homeSchTeam,$awaySchTeam);
    }
}
?>

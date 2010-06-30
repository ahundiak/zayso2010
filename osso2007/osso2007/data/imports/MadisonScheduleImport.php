<?php

class MadisonScheduleImport extends BaseScheduleImport
{
    function getSchTeamByKey($team)
    {
        $models = $this->context->models;
        
        if (!$team) return NULL;
        
        $aliases = array(
        	'894U10C1' =>  '894U10B1', // Tung Lee
        	'894U10C2' =>  '894U10G1', // Scott
            '894U10C3' => '9994U10B1', // Miller
            '894U10C4' =>  '894U10B2', // McCoy
            '894U10C5' =>  '894U10B3', // Morgan
            '894U10C6' =>  '894U10G2', // Etzel
        
        	'894U12C1'    =>  '894U12G1',  // Harness
        	'894U12C2'    => '9994U12B1',  // MA Cash
            '894U12C3'    =>  '894U12G2',  // Pitts
        	'498U16/19C1' =>  '498U19C1',
        	'498U16/19C2' =>  '498U19C2',
            '498U16/19C3' =>  '498U19C3',
            '498U16/19C4' =>  '498U19C4',
        );
        if (isset($aliases[$team])) $team = $aliases[$team];
        
        $temp = explode('U',$team);
        if (count($temp) != 2) die("Invalid team Key $team\n");
        
        // Grab the unit
        $unitNumber = (int)$temp[0];
        $unit = $models->UnitModel->searchByNumber($unitNumber);
        if (!$unit) die("Invalid unit number $team\n");
        
        // Grab the division
        $divLen = 3;
        $divKey = 'U' . substr($temp[1],0,$divLen);
        if ($unit->id != 4) {
            switch($divKey) {
                case 'U10C': $divKey = 'U10B'; break;
                case 'U12C': $divKey = 'U12B'; break;
              //case 'U14C': $divKey = 'U14B'; break;
            }    
        }
        $divId  = $models->DivisionModel->getDivisionIdForKey($divKey);
//      die("Division $divKey $divId\n");
        if (!$divId) die("Invalid age division $team\n");

        // Grab the seq number
        $teamSeqNum = (int)substr($temp[1],$divLen);
        
        // Use common routine
        return $this->getSchTeam($unit->id,$divId,$teamSeqNum);

    }
    function importRow($cellNodes)
    {
        //if (!isset($this->workSheetNames[$this->workSheetName])) return;
        
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
                        $ssType = $dataNode->getAttribute('ss:Type');
                        if ($ssType == 'DateTime') {
                            $game->date = $dataNodeValue;
                        }   
                        break;
                        
                    case 1: $game->time  = $dataNodeValue; break;
                    case 5: $game->field = $dataNodeValue; break;
                    
                    case 2: $game->awayCoach = $dataNodeValue; break;
                    case 3: $game->awayPhone = $dataNodeValue; break;
                    case 4: $game->awayTeam  = $dataNodeValue; break;
                    case 7: $game->homeCoach = $dataNodeValue; break;
                    case 8: $game->homePhone = $dataNodeValue; break;
                    case 6: $game->homeTeam  = $dataNodeValue; break;
                }
            }
        }
        
        // Make sure it a game row
        if ((!isset($game->date)) || (!$game->date)) return;
        if ((!isset($game->time)) || ($game->time == 'NO GAMES')) return;

        //echo "{$game->date} {$game->time} {$game->field} {$game->homeTeam} {$game->awayTeam}\n";
        //return;
        
        // Start the event stuff
        $event = $models->EventModel->find(0);
        $event->yearId         = $this->yearId;
        $event->seasonTypeId   = $this->seasonTypeId;
        $event->scheduleTypeId = $this->scheduleTypeId;
        $event->eventTypeId    = $this->eventTypeId;
        $event->point1         = $this->eventPoint1;
        $event->point2         = $this->eventPoint2;
        $event->unitId         = NULL;
        $event->status         = NULL;
        $event->duration       = 0;
        $event->fieldId        = 0;
        
        // Date
        $event->date = $models->DateTimeModel->getDateFromExcelFormat($game->date);
        if (strlen($event->date) != 8) {
            die("Invalid date {$game->date}\n");
        }
        //if ($event->date > $this->datex) return;
        
        // Time
        $event->time = $models->DateTimeModel->getTimeFromAmPmFormat($game->time);
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

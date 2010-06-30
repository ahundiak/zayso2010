<?php
class MonroviaU8ScheduleImport extends BaseScheduleImport
{
    protected $lastGameDate = NULL;
    
    function getSchTeamByKey($team)
    {
        if (!$team)                   return NULL;
        if (!strcasecmp($team,'Bye')) return NULL;
        
        // Division
        $divId = 0;
        if (substr($team,0,7) == 'U8 Boys') $divId = 4;
        if (substr($team,0,7) == 'U8 Girl') $divId = 5;
        if (!$divId) die('getU8SchTeam cannot determine gender');
        
        // Unit is easy
        $unitId = 1;
        
        // Sequence Number
        $parse = explode(' ',$team);
        $teamSeqNum = $parse[count($parse)-1];
        
        // Use common routine
        return $this->getSchTeam($unitId,$divId,$teamSeqNum);

    }

    public function importGame($game)
    {
        $models = $this->context->models;
        
        $event = $models->EventModel->find(0);
        $event->duration = 75;

        // The date
        $event->date = $models->DateTimeModel->getDateFromExcelFormat($game->date);
        if (strlen($event->date) != 8) {
            die("Invalid date {$game->date}\n");
        }
        // The time
        $event->time = $models->DateTimeModel->getTimeFromExcelFormat($game->time);
        if (strlen($event->time) != 4) {
            die("Invalid date {$game->time}\n");
        }
        // The field
        $event->fieldId = $game->field;
        
        // The teams
        $homeSchTeam = $this->getSchTeamByKey($game->home);
        $awaySchTeam = $this->getSchTeamByKey($game->away);
        
        // Create it
        $this->createEvent($event,$homeSchTeam,$awaySchTeam);
        
    }    
    public function importRow($cellNodes)
    {
        $game = new Game();
        $awayIndexes = array(3 => 75, 5 => 73, 7 => 76, 9 => 74);
        
        for ($cellIndex = 0; $cellIndex < $cellNodes->length; $cellIndex++) {
              
            $cellNode = $cellNodes->item($cellIndex);
            $dataNodes = $cellNode->getElementsByTagName('Data');
            $dataNode = $dataNodes->item(0);
            
            if ($dataNode) {
                $dataNodeValue = $dataNode->nodeValue;
                switch($cellIndex) {
                    
                    // Date Column
                    case 0:
                        $ssType = $dataNode->getAttribute('Type');
                        if ($ssType == 'DateTime') {
                            $this->lastGameDate = $game->date = $dataNode->nodeValue;
                        }
                        break;
                        
                    // Time Column
                    case 1:
                        $ssType = $dataNode->getAttribute('Type');
                        if ($ssType != 'DateTime') return;
                        $game->time = $dataNodeValue;
                        if (!isset($game->date)) $game->date = $this->lastGameDate;
                        break;
                        
                    // Home team columns
                    case 2:
                    case 4:
                    case 6:
                    case 8:
                        $game->home = $dataNodeValue;
                        break;
                        
                    // Away team columns
                    case 3:
                    case 5:
                    case 7:
                    case 9:
                        $game->away  = $dataNode->nodeValue;
                        $game->field = $awayIndexes[$cellIndex];
                        $this->importGame($game);
                        break;
                }
            }        
        }
    }
}
?>

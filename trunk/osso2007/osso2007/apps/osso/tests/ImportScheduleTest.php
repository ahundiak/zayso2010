<?php
class Game
{
}
class ImportScheduleTest extends BaseAppTest
{
    function initU8()
    {
        $model = $this->context->models->SchTeamModel;
        
        $search = new SearchData();
        $search->wantx         = TRUE;
        $search->wantPhyTeam   = TRUE;
        $search->wantCoachHead = TRUE;

        $search->unitId         = 1;
        $search->yearId         = 7;
        $search->seasonTypeId   = 1;
        $search->scheduleTypeId = 1;
        $search->divisionId     = 5; // U08G
        
        $items = $model->search($search);
        foreach($items as $item) {
            $this->teamGirls[$item->phyTeam->divisionSeqNum] = $item;
//          echo "{$item->id} {$item->phyTeam->divisionDesc} {$item->phyTeam->divisionSeqNum} {$item->phyTeam->coachHead->name}\n";
        }
        
        $search->divisionId     = 4; // U08B
        $items = $model->search($search);
        foreach($items as $item) {
            $this->teamBoys[$item->phyTeam->divisionSeqNum] = $item;
//          echo "{$item->id} {$item->phyTeam->divisionDesc} {$item->phyTeam->divisionSeqNum} {$item->phyTeam->coachHead->name}\n";
        }
        //Zend_Debug::dump($items);
//      die();  
    }
    function getU8SchTeam($team)
    {
        if (!$team) die('getU8SchTeam blank team');
        if (!strcasecmp($team,'Bye')) return NULL;
        
        $teams = NULL;
        if (substr($team,0,7) == 'U8 Boys') $teams = $this->teamBoys;
        if (substr($team,0,7) == 'U8 Girl') $teams = $this->teamGirls;
        if (!$teams) die('getU8SchTeam cannot determine gender');
        
        $parse = explode(' ',$team); //Zend_Debug::dump($parse); die();
        $teamSeqNum = $parse[count($parse)-1];
        if (!isset($teams[$teamSeqNum])) die("getU8SchTeam seq num $teamSeqNum");
        
        return $teams[$teamSeqNum];
    }
    function importU8Game($game)
    {
        if (!$game->row) die("importU8Game row not set");
        
        $eventModel = $this->context->models->EventModel;
        $event = $eventModel->find(0);
        $event->yearId = 7;
        $event->seasonTypeId   = 1;
        $event->scheduleTypeId = 1;
        $event->eventTypeId    = 1;
        $event->unitId         = 1;
        $event->status         = 1;
        $event->duration       = 75;
        
        // Currently being set
        $event->unitId         = NULL;
        $event->status         = NULL;
        
        $event->fieldId = $game->field;
        if (!$game->field) die("importU8Game field not set");
        
        $event->date = substr($game->date,0,4) . substr($game->date,5,2) . substr($game->date,8,2);
        if (!$game->date) die("importU8Game date not set");
        
        $event->time = substr($game->time,11,2) . substr($game->time,14,2);
        if (!$game->time) die("importU8Game time not set");
        
        // At this point should query to see if the game at that field already exists
        $events = $eventModel->search($event);
        if (count($events)) {
            $eventx = array_pop($events);
            echo "Existing game {$eventx->id}\n";
            return;
        }
        
        // Need the two teams
        $homeSchTeam = $this->getU8SchTeam($game->home);
        $awaySchTeam = $this->getU8SchTeam($game->away);
        
        if ((!$homeSchTeam) && (!$awaySchTeam)) {
            die("importU8Game missing home and away team");
        }
        
        // Create the event
        if (!$homeSchTeam) $event->time = 'BN';
        if (!$awaySchTeam) $event->time = 'BN';
        
        $eventId = $eventModel->save($event);
        if (!$eventId) die('importU8Game event not created');
        
        // Do the teams
        $eventTeamModel = $this->context->models->EventTeamModel;
        $eventTeam = $eventTeamModel->find(0);
        $eventTeam->eventId = $eventId;
        $eventTeam->score   = 0;
        
        if ($homeSchTeam) {
            $eventTeam->schTeamId  = $homeSchTeam->id;
            $eventTeam->eventTeamTypeId = 1;
            $eventTeam->yearId     = $homeSchTeam->yearId;
            $eventTeam->unitId     = $homeSchTeam->unitId;
            $eventTeam->divisionId = $homeSchTeam->divisionId;
            $eventTeamId = $eventTeamModel->save($eventTeam);
            if (!$eventTeamId) die('importU8Game home event team not created');   
        }
        if ($awaySchTeam) {
            $eventTeam->schTeamId  = $awaySchTeam->id;
            $eventTeam->eventTeamTypeId = 2;
            $eventTeam->yearId     = $awaySchTeam->yearId;
            $eventTeam->unitId     = $awaySchTeam->unitId;
            $eventTeam->divisionId = $awaySchTeam->divisionId;
            $eventTeamId = $eventTeamModel->save($eventTeam);
            if (!$eventTeamId) die('importU8Game away event team not created');   
        }
        
//        echo "{$event->date} {$event->time} {$event->fieldId} {$game->home} {$game->away} {$homeSchTeamId} {$awaySchTeamId}\n";
//        Zend_Debug::dump($event);
        
    }
    function testU8()
    {
        $xmlReader = new XMLReader();
        $flag = $xmlReader->open('/ahundiak/misc/soccer2007/fall/schedule/U8V002 schedule.xml');
        $this->assertTrue($flag);

        $game = new Game();
        $homeIndexes = array(2 =>  2, 4 =>  4, 6 =>  6, 8 =>  8);
        $awayIndexes = array(3 => 75, 5 => 73, 7 => 76, 9 => 74);

        $this->initU8();
                
        while($xmlReader->read()) {
            if ($xmlReader->nodeType == XMLReader::ELEMENT) {
                if ($xmlReader->name == 'Row') {
                    $game->row = FALSE;
                    $rowNode = $xmlReader->expand();
                    $cellNodes = $rowNode->getElementsByTagName('Cell');
                    
                    for ($cellIndex = 0; $cellIndex < $cellNodes->length; $cellIndex++) {    
                        $cellNode = $cellNodes->item($cellIndex);
                        $dataNodes = $cellNode->getElementsByTagName('Data');
                        $dataNode = $dataNodes->item(0);
                        if ($dataNode) {
                            if ($cellIndex == 0) {
                                $ssType = $dataNode->getAttribute('Type');
                                if ($ssType == 'DateTime') {
                                    $game->date = $dataNode->nodeValue;
                                }
                            }
                            if ($cellIndex == 1) {
                                $ssType = $dataNode->getAttribute('Type');
                                if ($ssType == 'DateTime') {
                                    $game->row = TRUE;
                                    $game->time = $dataNode->nodeValue;
                                    $game->home = NULL;
                                    $game->away = NULL;
                                }
                            }
                            if (isset($homeIndexes[$cellIndex])) {
                                if ($game->row) $game->home = $dataNode->nodeValue;   
                            }
                            if (isset($awayIndexes[$cellIndex])) {
                                if ($game->row) {
                                    $game->away  = $dataNode->nodeValue;
                                    $game->field = $awayIndexes[$cellIndex];
                                    $this->importU8Game($game);
//                                    Zend_Debug::dump($game);
                                }
                            }
                        }
                    }
                    //if ($game->row) die();
                }
            }  
        }
        
        $xmlReader->close();
    }    
}
?>

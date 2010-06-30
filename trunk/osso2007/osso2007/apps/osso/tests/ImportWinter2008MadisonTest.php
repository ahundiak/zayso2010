<?php
class Game
{
}
class ImportScheduleMadisonTest extends BaseAppTest
{
    function getMadisonSchTeam($team)
    {
        if (!$team) return NULL;
        
        $temp = explode('U',$team);
        
    }
    function importMadisonScheduleRow($cellNodes)
    {
        $game = new Game();
        
        // Extract game info
        for ($cellIndex = 0; $cellIndex < $cellNodes->length; $cellIndex++) {    
            $cellNode = $cellNodes->item($cellIndex);
            $dataNodes = $cellNode->getElementsByTagName('Data');
            $dataNode = $dataNodes->item(0);
            if ($dataNode) {
                $dataNodeValue = $dataNode->nodeValue;
                switch ($cellIndex) {
                    case 0:
                        $ssType = $dataNode->getAttribute('Type');
                        if ($ssType == 'DateTime') {
                            $game->date = $dataNodeValue;
                        }   
                        break;
                    case 1:
                        $game->time = $dataNodeValue;
                        break;
                    case 5:
                        $game->field = $dataNodeValue;
                        break;
                    case 2:
                        $game->awayCoach = $dataNodeValue;
                        break;
                    case 3:
                        $game->awayPhone = $dataNodeValue;
                        break;
                    case 4:
                        $game->awayTeam = $dataNodeValue;
                        break;
                    case 7:
                        $game->homeCoach = $dataNodeValue;
                        break;
                    case 8:
                        $game->homePhone = $dataNodeValue;
                        break;
                    case 6:
                        $game->homeTeam = $dataNodeValue;
                        break;
                }
            }
        }
        // Make sure it a game row
        if ((!isset($game->date)) || (!$game->date)) return;
     
        // Grab the teams
        $homeSchTeam = $this->getMadisonSchTeam($game->homeTeam);
           
        Zend_Debug::dump($game); die();
    }
    function importMadisonSchedule($xmlFileName)
    {
        $xmlReader = new XMLReader();
        $flag = $xmlReader->open($xmlFileName);
        $this->assertTrue($flag);
        
        while($xmlReader->read()) {
            if ($xmlReader->nodeType == XMLReader::ELEMENT) {
                if ($xmlReader->name == 'Row') {
                    $rowNode = $xmlReader->expand();
                    $cellNodes = $rowNode->getElementsByTagName('Cell');
                    $this->importMadisonScheduleRow($cellNodes);
                }
            }
        }
        $xmlReader->close();
    }
    function testMadison()
    {
        $this->importMadisonSchedule('/ahundiak/misc/soccer2008/winter/schedule/Madison/All.xml');
    }
}
?>

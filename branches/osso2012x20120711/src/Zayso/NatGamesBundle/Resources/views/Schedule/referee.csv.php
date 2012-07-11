<?php

// Start with a virtual file
$fp = fopen('php://temp','r+');

// Header
$row = array(
    "Game","Date","DOW","Time","Field",
    "Pool","Home Team","Away Team",
    "Referee","Asst Referee 1","Asst Referee 2",
);
fputcsv($fp,$row);

// Games
foreach($games as $game)
{
    // Date
    $date = $game->getDate();
    $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
    $dow  = date('D',  $stamp);
    $date = date('M d',$stamp);
    
    // Time
    $time = $game->getTime();
    $stamp = mktime(substr($time,0,2),substr($time,2,2));
    $time = date('h:i A',$stamp);
 
    $row = array();
    $row[] = $game->getNum();
    $row[] = $date;
    $row[] = $dow;
    $row[] = $time;
    $row[] = $game->getFieldDesc();
    
    $row[] = $game->getPool();
    $row[] = $game->getHomeTeam()->getTeam()->getDesc();
    $row[] = $game->getAwayTeam()->getTeam()->getDesc();
    
    foreach($game->getEventPersonsSorted() as $gamePersonRel)
    {
        $row[] = $gamePersonRel->getPersonz()->getPersonName();
    }
    fputcsv($fp,$row);
}
// Return the content
rewind($fp);
$csv = stream_get_contents($fp);
fclose($fp);
echo $csv;
return;

?>
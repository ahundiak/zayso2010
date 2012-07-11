<?php

// Start with a virtual file
$fp = fopen('php://temp','r+');

// Header
$row = array(
    "GameNo","GameDate","GameTime","GameField",
    "GameDiv","GameGender","DivGroup","GameWeekNo",
    "GameHome","GameVis","GamePrac",
    "HomeGoals","VisGoals","HomeRefPoint","VisRefPoint","GameNotes",);
fputcsv($fp,$row);

// "00X10B9","8/27/2011","11:45:00 AM","","X10","B","","","","X10B01","","","","","","",

// Games
foreach($games as $game)
{
    // Date
    $date = $game->getDate();
    $day  = (int)substr($date,6,2);
    $mon  = (int)substr($date,4,2);
    $year = (int)substr($date,0,4);
    $date = sprintf('%d/%d/%d',$mon,$day,$year);
    
    // Time
    $time = $game->getTime();
    $hour = (int)substr($time,0,2);
    $min  = (int)substr($time,2,2);
    if ($hour >= 12) 
    {
        if ($hour > 12) $hour -= 12;
        $suffix = 'PM';
    }
    else $suffix = 'AM';
    
    $time = sprintf('%d:%02d:00 %s',$hour,$min,$suffix);
    
    $homeTeam = $game->getHomeTeam()->getTeam();
    $awayTeam = $game->getAwayTeam()->getTeam();
    
    $age    = $homeTeam->getAge();
    $gender = $homeTeam->getGender();
    
    $row = array();
    $row[] = $game->getProject()->getId() . '-' . $game->getNum();
    $row[] = $date;
    $row[] = $time;
    $row[] = $game->getFieldDesc();
    
    $row[] = $age;
    $row[] = $gender;
    $row[] = $game->getPool(); // DivGroup
    $row[] = null;
    
    $row[] = $homeTeam->getDesc();
    $row[] = $awayTeam->getDesc();
    $row[] = null; // Practice
    
    $row[] = null; // Home Goals
    $row[] = null; // Away Goals
    
    $row[] = null; // HomeRefPoint
    $row[] = null; // AwayRefPoint
    $row[] = null; // Notes
    
    fputcsv($fp,$row);
}
// Return the content
rewind($fp);
$csv = stream_get_contents($fp);
fclose($fp);
echo $csv;
return;

echo "AP ID,Account,First Name,Last  Name,Nick  Name,Email,Cell Phone,";
echo "Region,Region Desc,State,AYSOID,DOB,Gender,Ref Badge,Ref Date,Safe Haven,MY,";
echo "Attend,Referee,Sun,Mon,Tue,Wed,Thu,Fri,Sat,Sun\n";

foreach($members as $member)
{
    if (1) {
    $memberx->setMember($member);
    echo $member->getId() . ',';
    echo $memberx->getUserName()  . ',';
    echo $memberx->getFirstName() . ',';
    echo $memberx->getLastName()  . ',';
    echo $memberx->getNickName()  . ',';
    echo $memberx->getEmail()     . ',';
    echo $memberx->getCellPhone() . ',';
    
    echo $memberx->getRegion()      . ',';
    
    echo '"' . $memberx->getRegionDesc() . '"'  . ',';
    
    echo $memberx->getRegionState() . ',';
    
    echo $memberx->getAysoid()    . ',';
    echo $memberx->getDob()       . ',';
    echo $memberx->getGender()    . ',';
    echo $memberx->getRefBadge()  . ',';
    echo $memberx->getRefDate()   . ',';
    echo $memberx->getSafeHaven() . ',';
    echo $memberx->getMemYear()   . ',';
    echo $memberx->getPlan('attend') . ',';
    echo $memberx->getPlan('will_referee') . ',';
    echo $memberx->getPlan('room_sun0') . ',';    
    echo $memberx->getPlan('room_mon0') . ',';    
    echo $memberx->getPlan('room_tue1') . ',';    
    echo $memberx->getPlan('room_wed1') . ',';    
    echo $memberx->getPlan('room_thu1') . ',';    
    echo $memberx->getPlan('room_fri1') . ',';    
    echo $memberx->getPlan('room_sat1') . ',';    
    echo $memberx->getPlan('room_sun1');    
    echo "\n"; }
}
?>

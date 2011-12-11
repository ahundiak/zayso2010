<?php
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

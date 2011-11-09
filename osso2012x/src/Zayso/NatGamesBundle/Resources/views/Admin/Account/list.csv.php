<?php
echo "ID,Account,First Name,Last  Name,Nick  Name,Email,Cell Phone,Region,AYSOID,DOB,Gender,Ref Badge,Ref Date,Safe Haven,MY,Attend,Referee\n";
foreach($members as $member)
{
    $memberx->setMember($member);
    echo $member->getId() . ',';
    echo $memberx->getUserName()  . ',';
    echo $memberx->getFirstName() . ',';
    echo $memberx->getLastName()  . ',';
    echo $memberx->getNickName()  . ',';
    echo $memberx->getEmail()     . ',';
    echo $memberx->getCellPhone() . ',';
    echo $memberx->getRegion()    . ',';
    echo $memberx->getAysoid()    . ',';
    echo $memberx->getDob()       . ',';
    echo $memberx->getGender()    . ',';
    echo $memberx->getRefBadge()  . ',';
    echo $memberx->getRefDate()   . ',';
    echo $memberx->getSafeHaven() . ',';
    echo $memberx->getMemYear()   . ',';
    echo $memberx->getPlan('attend') . ',';
    echo $memberx->getPlan('will_referee');
    echo "\n";
}
?>

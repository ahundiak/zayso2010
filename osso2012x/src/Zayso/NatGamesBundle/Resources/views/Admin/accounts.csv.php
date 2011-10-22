<?php
echo "ID,Account,Ver,First Name,Last  Name,Nick  Name,Email,Cell Phone,Region,AYSOID,Attend,Referee\n";
foreach($accounts as $account)
{
    foreach($account->getMembers() as $member)
    {
        $memberx->setMember($member);
        echo $account->getId() . ',';
        echo $account->getUsername()  . ',';
        echo $member->getVerified()   . ',';
        echo $memberx->getFirstName() . ',';
        echo $memberx->getLastName()  . ',';
        echo $memberx->getNickName()  . ',';
        echo $memberx->getEmail()     . ',';
        echo $memberx->getCellPhone() . ',';
        echo $memberx->getRegion()    . ',';
        echo $memberx->getAysoid()    . ',';
        echo $memberx->getPlan('attend')       . ',';
        echo $memberx->getPlan('will_referee');
    }
    echo "\n";
}
?>

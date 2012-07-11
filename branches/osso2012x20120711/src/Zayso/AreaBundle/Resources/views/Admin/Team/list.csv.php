<?php

echo get_class($app->getSession());
die('session');
echo $app->getSession()->get();

$fp = fopen('php://temp','r+');
$headers = array
(
    'AP ID','Account','First Name','Last  Name','Nick  Name','Email','Cell Phone',
    'Region','Region Desc','State','AYSOID','DOB','Gender','Ref Badge','Ref Date','Safe Haven','MY',
);
fputcsv($fp,$headers);

foreach($accountPersons as $accountPerson)
{
    $accountPersonx->setaccountPerson($accountPerson);
    $row = array
    (        
        $accountPerson->getId(),
        $accountPersonx->getUserName(),
        $accountPersonx->getFirstName(),
        $accountPersonx->getLastName(),
        $accountPersonx->getNickName(),
        $accountPersonx->getEmail(),
        $accountPersonx->getCellPhone(),
        
        $accountPersonx->getRegion(),
        $accountPersonx->getRegionDesc(),
        $accountPersonx->getRegionState(),

        $accountPersonx->getAysoid(),
        $accountPersonx->getDob(),
        $accountPersonx->getGender(),
        $accountPersonx->getRefBadge(),
        $accountPersonx->getRefDate(),
        $accountPersonx->getSafeHaven(),
        $accountPersonx->getMemYear(),
    );
    fputcsv($fp,$row);
}
rewind($fp);
$csv = stream_get_contents($fp);
fclose($fp);
echo $csv;
?>

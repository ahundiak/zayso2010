<?php
  $data  = $this->data;
  $items = $data->items;
?>
UID,User,AYSOID,Region,First Name,Nick Name,Last Name,Full Name,Cell 1,Cell 2,Email 1,Email 2,Safe Haven,Referee Badge,MY,DOB
<?php
  foreach($items as $item)
  {
    $line = array();
    
    $line[] = $item->getId();
    $line[] = $item->getAccountUserName();
    $line[] = $item->getAysoid();
    $line[] = $item->getRegion();

    $nname = $item->getPersonNickName();

    $lname = $item->getPersonLastName();
    if (!$lname) $lname = $item->getAccountLastName();
    
    $fname = $item->getPersonFirstName();
    if (!$fname) $fname = $item->getAccountFirstName();

    $line[] = $fname;
    $line[] = $nname;
    $line[] = $lname;

    $name = $fname . ' ';
    if ($nname) $name .= '"' . $nname . '" ';
    $name .= $lname;

    $line[] = $name;

    $cellPhone = $item->getPersonCellPhone();
    if ($cellPhone)
    {
      $cellPhone = substr($cellPhone,0,3) . '.' . substr($cellPhone,3,3) . '.' . substr($cellPhone,6);
    }
    $line[] = $item->getAccountCellPhone();
    $line[] = $cellPhone;

    $line[] = $item->getAccountEmail();
    $line[] = $item->getPersonEmail();

    $line[] = $item->getSafeHavenDesc();
    $line[] = $item->getRefereeBadgeDesc();
    $line[] = 'MY' . (int)$item->getRegYear();
    $line[] = 'DOB' . $item->getPersonDOB();
 
    echo implode(',',$line) . "\n";
}
?>

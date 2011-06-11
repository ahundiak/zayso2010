<?php
  // Session Show Tpl
  $items = $this->data->items;
?>
<div>
<table border = "1" width="900">
<tr><th colspan="5">Account Information <?php echo count($items); ?></th></tr>
<tr>
  <td width="25">ID</td>
  <td width="75">User Name</td>
  <td width="100">Account Information</td>
  <td width="50">AYSOID</td>
  <td width="100">AYSO Information</td>
</tr>
<?php
  foreach($items as $item)
  {
?>
<tr>
  <td><?php echo $item->getId();              ?></td>
  <td><?php echo $item->getAccountUserName(); ?></td>
  <td><?php
    $html = '';
    $html .= $this->escape($item->getAccountFirstName()) . ' ';
    $html .= $this->escape($item->getAccountLastName())  . '<br />';
    $html .= $this->escape($item->getAccountEmail())     . '<br />';
    $html .= $this->escape($item->getAccountCellPhone());
    echo $html . "\n";;
  ?>
  </td>
  <td><?php echo $item->getAysoid();          ?></td>
  <td><?php
    $safeHaven = $this->escape($item->getSafeHavenDesc());
    if (!$safeHaven) $safeHaven = '<span style="color: red">No SafeHaven</span>';

    $refBadge = $this->escape($item->getRefereeBadgeDesc());
    if (!$refBadge) $refBadge = '<span style="color: red">No Referee Cert</span>';

    $regYear = (int)$item->getRegYear();
    if ($regYear < 2010) $regYear = '<span style="color: red">' . $regYear . '</span>';

    $dob = $item->getPersonDOB();
    $dob = substr($dob,0,4);

    $nname = $this->escape($item->getPersonNickName());

    $cellPhone = $item->getPersonCellPhone();
    if ($cellPhone)
    {
      $cellPhone = substr($cellPhone,0,3) . '.' . substr($cellPhone,3,3) . '.' . substr($cellPhone,6);
    }
    $html = '';

    $html .=         $this->escape($item->getRegion()) . ' ';
    $html .= 'MY'  . $regYear                          . ' ';
    $html .= 'DOB' . $dob . '<br />';

    $html .=         $safeHaven . '<br />';
    $html .=         $refBadge  . '<br />';

    if ($nname) {
      $html .= 'Nick Name: ' . $this->escape($item->getPersonNickName())  . '<br />';
    }
    $html .= $this->escape($item->getPersonEmail())     . '<br />';
    if ($cellPhone) {
      $html .= 'C: ' . $cellPhone;
    }
    echo $html . "\n";;
  ?></td>
    <?php /*
  <td><?php
    $dob = $item->getPersonDOB();
    $dob = substr($dob,0,4);
    $html = '';
    $html .= 'DOB' . $dob . ' ';
    $html .= $this->escape($item->getPersonNickName())  . '<br />';
    $html .= $this->escape($item->getPersonEmail())     . '<br />';
    $html .= $this->escape($item->getPersonCellPhone());
    echo $html . "\n";;
  ?>
     *
     */ ?>
</tr>
<?php } ?>
</table>
</div>


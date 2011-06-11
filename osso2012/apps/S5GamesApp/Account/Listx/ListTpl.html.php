<?php
  // Session Show Tpl
  $items = $this->data->items;
?>
<div>
<table border = "1" width="900">
<tr><th colspan="6">Account Information</th></tr>
<tr>
  <td width="50">ID</td>
  <td width="100">User Name</td>
  <td width="100">Account Information</td>
  <td width="50">AYSOID</td>
  <td width="100">AYSO Certs</td>
  <td width="100">AYSO Contact</td>
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
    $refBadge = $this->escape($item->getRefereeBadgeDesc());
    if (!$refBadge) $refBadge = '<span style="color: red">No Referee Cert</span>';

    $html = '';
    $html .=        $this->escape($item->getRegion())        . ' ';
    $html .= 'MY' . $this->escape($item->getRegYear())       . '<br />';
    $html .=        $this->escape($item->getSafeHavenDesc()) . '<br />';
    $html .=        $refBadge;
    echo $html . "\n";;
  ?>
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
</tr>
<?php } ?>
</table>
</div>


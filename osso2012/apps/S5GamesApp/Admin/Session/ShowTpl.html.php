<?php
  // Session Show Tpl
  $items = $this->data->items;
?>
<div>
<table border = "1" width="800">
<tr><th colspan="5">Session Information</th></tr>
<tr>
  <td width="45">ID</td>
  <td width="200">Session ID</td>
  <td width="100">Updated</td>
  <td width="100">Name</td>
  <td width="150">Account</td>
</tr>
<?php
  foreach($items as $item)
  {
    $ts = $item->getUpdated();
    $updated = substr($ts,0,8) . '-' . substr($ts,8);
?>
<tr>
  <td><?php echo $item->getId();        ?></td>
  <td><?php echo $item->getSessionId(); ?></td>
  <td><?php echo $updated;              ?></td>
  <td><?php echo $item->getName();      ?></td>
  <td><?php echo $item->userName;       ?></td>
</tr>
<?php } ?>
</table>
</div>


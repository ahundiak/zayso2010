<?php $data = $this->unitListData; ?>
<table border="1">
<tr><th colspan="7">List Organizations</th></tr>
<tr>
    <td>Edit</td>
    <td>Type</td.
    <td>Sort</td>
    <td>Key</td>
    <td>Desc Pick</td>
    <td>Prefix</td>
    <td>Desc Long</td>
</tr>
<?php 
    foreach($this->unitItems as $item) {
        $itemId = $item->id;
?>
<tr>
    <td><?php echo $this->href('Edit','unit_edit',$itemId); ?></td>
    <td><?php echo $this->escape($item->unitTypeDesc); ?></td>
    <td><?php echo $this->escape($item->sort);         ?></td>
    <td><?php echo $this->escape($item->key);          ?></td>
    <td><?php echo $this->escape($item->descPick);     ?></td>
    <td><?php echo $this->escape($item->prefix);       ?></td>
    <td><?php echo $this->escape($item->descLong);     ?></td>
</tr>
<?php } ?>
<tr>
    <td colspan="7"><?php echo $this->href('Create New Organization','unit_edit',0); ?></td>
</tr>
</table>

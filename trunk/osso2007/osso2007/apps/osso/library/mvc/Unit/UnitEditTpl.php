<?php $item = $this->unitItem; ?>

<form method="post" action="<?php echo $this->link('unit_edit'); ?>"> 
<table border="1">
<tr>
  <th colspan="2">
  <?php 
    if ($item->id) echo "Edit Organization: {$item->descPick}";
    else           echo "Create New Organization";
  ?>
  </th>
</tr>
<tr>
    <td>ID</td>
    <td><?php echo $item->id; ?></td>
    <input type="hidden" name="unit_id" value="<?php echo $item->id; ?>" />
</tr>
<tr>
    <td>Organization Type</td>
    <td>
        <select name="unit_type_id">
            <option value="0">Organization Type</option>
            <?php echo $this->formOptions($this->unitTypePickList,$item->unitTypeId); ?>" />
        </select>
    </td>
</tr>
<tr>
    <td>Sort By</td>
    <td><input type="text" name="unit_sort" value="<?php echo $item->sort; ?>" /></td>
</tr>
<tr>
    <td>Unique Key</td>
    <td><input type="text" name="unit_key" value="<?php echo $item->key; ?>" /></td>
</tr>
<tr>
    <td>Prefix</td>
    <td><input type="text" name="unit_prefix" value="<?php echo $item->prefix; ?>" /></td>
</tr>
<tr>
    <td>Pick List Description</td>
    <td><input type="text" name="unit_desc_pick" value="<?php echo $item->descPick; ?>" /></td>
</tr>
<tr>
    <td>Long Description</td>
    <td><input type="text" name="unit_desc_long" value="<?php echo $item->descLong; ?>" size="40" /></td>
</tr>
<tr>
    <td><?php echo $this->href('Unit List','unit_list'); ?></td>
    <td><?php echo $this->formUDC('unit',$item->id); ?></td>
</tr>
</table>
</form>

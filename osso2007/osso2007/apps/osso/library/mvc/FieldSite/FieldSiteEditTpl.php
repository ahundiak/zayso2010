<?php $item = $this->fieldSiteItem; ?>

<form method="post" action="<?php echo $this->link('field_site_edit'); ?>"> 
<table border="1">
<tr>
    <th colspan="2">
        <?php 
            if ($item->id) echo "Edit Field Site: {$item->desc}";
            else           echo "Create New Field Site";
        ?>
    </th>
</tr>
<tr>
    <td>ID</td>
    <td><?php echo $item->id; ?></td>
    <input type="hidden" name="field_site_id" value="<?php echo $item->id; ?>" />
</tr>
<tr>
    <td>Region</td>
    <td>
        <select name="field_site_unit_id">
            <option value="0">Select Region</option>
            <?php echo $this->formOptions($this->unitPickList,$item->unitId); ?>" />
        </select>
    </td>
</tr>
<tr>
    <td>Sort By</td>
    <td><input type="text" name="field_site_sort" value="<?php echo $item->sort; ?>" /></td>
</tr>
<tr>
    <td>Unique Key</td>
    <td><input type="text" name="field_site_key" value="<?php echo $item->key; ?>" /></td>
</tr>
<tr>
    <td>Pick List Description</td>
    <td><input type="text" name="field_site_desc" value="<?php echo $item->desc; ?>" size="30" /></td>
</tr>
<tr>
    <td><?php echo $this->href('Field Site List','field_site_list'); ?></td>
    <td><?php echo $this->formUDC('field_site',$item->id); ?></td>
</tr>
</table>
</form>

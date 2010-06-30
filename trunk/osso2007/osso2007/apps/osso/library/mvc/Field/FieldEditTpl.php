<?php $item = $this->fieldItem; ?>

<form method="post" action="<?php echo $this->link('field_edit'); ?>"> 
<table border="1">
<tr>
    <th colspan="2">
        <?php 
            if ($item->id) echo "Edit Field: {$item->desc}";
            else           echo "Create New Field";
        ?>
    </th>
</tr>
<tr>
    <td>ID</td>
    <td><?php echo $item->id; ?></td>
    <input type="hidden" name="field_id" value="<?php echo $item->id; ?>" />
</tr>
<tr>
    <td>Region</td>
    <td>
        <select name="field_unit_id">
            <option value="0">Region</option>
            <?php echo $this->formOptions($this->unitPickList,$item->unitId); ?>" />
        </select>
    </td>
</tr>
<tr>
    <td>Field Site</td>
    <td>
        <select name="field_site_id">
            <option value="0">Field Site</option>
            <?php echo $this->formOptions($this->fieldSitePickList,$item->fieldSiteId); ?>" />
        </select>
    </td>
</tr><tr>
    <td>Sort By</td>
    <td><input type="text" name="field_sort" value="<?php echo $item->sort; ?>" /></td>
</tr>
<tr>
    <td>Unique Key</td>
    <td><input type="text" name="field_key" value="<?php echo $item->key; ?>" /></td>
</tr>
<tr>
    <td>Description</td>
    <td><input type="text" name="field_desc" value="<?php echo $item->desc; ?>" /></td>
</tr>
<tr>
    <td><?php echo $this->href('Field List','field_list'); ?></td>
    <td><?php echo $this->formUDC('field',$item->id); ?></td>
</tr>
</table>
</form>

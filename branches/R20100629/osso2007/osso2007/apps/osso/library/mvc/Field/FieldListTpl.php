<?php $data = $this->fieldListData; ?>

<form method="post" action="<?php echo $this->link('field_list'); ?>">
<table border="1">
<tr><th colspan="5">Search Fields</th></tr>
<tr>
    <td>Region</td>
    <td>
        <select name="field_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?>" />
        </select>
    </td>
    <td>Field Sites</td>
    <td>
        <select name="field_field_site_id">
            <option value="0">All Sites</option>
            <?php echo $this->formOptions($this->fieldSitePickList,$data->fieldSiteId); ?>" />
        </select>
    </td>
    <td><input type="submit" name="field_submit_search" value="Search" />
</tr>
<tr>
    <td colspan="5">
        <?php echo $this->href('Create New Field','field_edit',0,$data->fieldSiteId); ?>
        <?php echo $this->href('List Field Sites','field_site_list'); ?>
    </td>
</tr>
</table>
</form>
<br />
<table border="1">
<tr><th colspan="5">List Fields</th></tr>
<tr>
    <td>Edit Field</td>
    <td>Field Site</td>
    <td>Sort</td>
    <td>Key</td>
    <td>Description</td>
</tr>
<?php foreach($this->fields as $field) { ?>
<tr>
    <td><?php echo $this->href('Edit Field','field_edit',$field->id); ?></td>
    <td><?php echo $this->escape($field->fieldSiteDesc); ?></td>
    <td><?php echo $this->escape($field->sort);          ?></td>
    <td><?php echo $this->escape($field->key);           ?></td>
    <td><?php echo $this->escape($field->desc);          ?></td>
</tr>
<?php } ?>
</table>

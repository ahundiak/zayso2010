<?php $data = $this->fieldSiteListData; ?>

<form method="post" action="<?php echo $this->link('field_site_list'); ?>">
<table border="1">
<tr><th colspan="5">Search Field Sites</th></tr>
<tr>
    <td>Region</td>
    <td>
        <select name="field_site_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?>" />
        </select>
    </td>
    <td>Site Name</td>
    <td>
        <input type="text" name="field_site_name" size="20" 
            value="<?php echo $this->escape($data->name); ?>" />
    </td>
    <td><input type="submit" name="field_site_submit_search" value="Search" />
</tr>
<tr>
    <td colspan="5">
        <?php echo $this->href('Create New Field Site','field_site_edit',0); ?>
    </td>
</tr>
</table>
</form>
<br />
<table border="1">
<tr><th colspan="5">List Field Sites</th></tr>
<tr>
    <td>Edit Site</td>
    <td>List Fields</td>
    <td>Sort</td>
    <td>Key</td>
    <td>Description</td>
</tr>
<?php 
    foreach($this->fieldSites as $fieldSite) {
?>
<tr>
    <td><?php echo $this->href('Edit','field_site_edit',$fieldSite->id); ?></td>
    <td><?php echo $this->href('List','field_list',     $fieldSite->unitId,$fieldSite->id); ?></td>
    <td><?php echo $this->escape($fieldSite->sort);  ?></td>
    <td><?php echo $this->escape($fieldSite->key);   ?></td>
    <td><?php echo $this->escape($fieldSite->desc);  ?></td>
</tr>
<?php } ?>
</table>

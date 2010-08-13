<h3>List People</h3>
<?php $data = $this->personListData; ?>

<form method="post" action="<?php echo $this->link('person_list'); ?>">
<table border="1">
<tr><th colspan="4">Search and List People</th></tr>
 
<tr>
    <td>Person ID</td>
    <td>
        <input type="text" name="person_id" size="4" value="<?php echo $data->personId; ?>" />
    </td>
    <td>Year</td>
    <td>
        <select name="person_year_id">
            <option value="0">All Years</option>
            <?php echo $this->formOptions($this->yearPickList,$data->yearId); ?>
        </select>
    </td>
</tr>
<tr>
    <td>First Name</td>
    <td>
        <input type="text" name="person_fname" size="20" 
            value="<?php echo $this->escape($data->fname); ?>" />
    </td>
    <td>Season</td>
    <td>
        <select name="person_season_type_id">
            <option value="0">All Seasons</option>
            <?php echo $this->formOptions($this->seasonTypePickList,$data->seasonTypeId); ?>
        </select>
    </td>
</tr>
<tr>
    <td>Last Name</td>
    <td>
        <input type="text" name="person_lname" size="20" 
            value="<?php echo $this->escape($data->lname); ?>" />
    </td>
    <td>Region</td>
    <td>
        <select name="person_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$data->unitId); ?>
        </select>
    </td>
</tr>
<tr>
    <td></td>
    <td></td>
    <td>Job</td>
    <td>
        <select name="person_vol_type_id">
            <option value="0">All Jobs</option>
            <?php echo $this->formOptions($this->volTypePickList,$data->volTypeId); ?>
        </select>
    </td>
</tr>
<tr>
    <td colspan="3">
        <?php echo $this->href('Create New Person','person_edit',0); ?>
    </td>
    <td><input type="submit" name="person_submit_search" value="Search" />
</tr>
</table>
</form>
<br />
<table border="1">
<tr><th colspan="4">List of People</th></tr>
<tr>
    <td>Edit</td>
    <td style="width: 200px;">Person's Name</td>
    <td style="width: 100px;">Eayso Info</td>
    <td>Volunteer Positions</td>
</tr>
<?php 
    foreach($this->personItems as $item) {
?>
<tr>
    <td><?php echo $this->href('Edit','person_edit',$item->id); ?></td>
    <td><?php echo $this->displayPersonName($item);       ?></td>
    <td><?php echo $this->escape($item->aysoid);          ?></td>
    <td><?php echo $this->displayVolList   ($item->vols); ?></td>
</tr>
<?php } ?>
</table>

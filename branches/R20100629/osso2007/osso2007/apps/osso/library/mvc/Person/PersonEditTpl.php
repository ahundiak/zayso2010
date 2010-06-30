<?php $person = $this->person; ?>

<form method="post" action="<?php echo $this->link('person_edit'); ?>"> 
<table border="1">
<tr><th colspan="2">Edit Person <?php echo $this->displayPersonName($person); ?></th></tr>
<tr>
    <td>ID</td>
    <td><?php echo $this->escape($person->id); ?></td>
    <input type="hidden" name="person_id" value="<?php echo $person->id; ?>" />
</tr>
<tr>
    <td>First Name</td>
    <td><input type="text" name="person_fname" value="<?php echo $this->escape($person->fname); ?>" /></td>
</tr>
<tr>
    <td>Last Name</td>
    <td><input type="text" name="person_lname" value="<?php echo $this->escape($person->lname); ?>" /></td>
</tr>
<tr>
    <td>AYSO ID</td>
    <td><input type="text" name="person_aysoid" value="<?php echo $this->escape($person->aysoid); ?>" /></td>
</tr>
<tr>
    <td>Region</td>
    <td>
        <select name="person_unit_id">
            <option value="0">Select Region</option>
            <?php echo $this->formOptions($this->unitPickList,$person->unitId); ?>" />
        </select>
    </td>
</tr>
<?php foreach($this->phones as $phone) { $phoneId = $phone->id; ?>
<tr>
    <td>Phone <?php echo $this->displayPhoneType($phone); ?></td>
    <td>
        <input type="hidden" name="phone_ids[<?php echo $phoneId; ?>]"      value="<?php echo $phone->id; ?>" />
        <input type="hidden" name="phone_type_ids[<?php echo $phoneId; ?>]" value="<?php echo $phone->phoneTypeId; ?>" />
        <input type="text"   name="phone_numbers[<?php echo $phoneId; ?>]"  value="<?php echo $this->displayPhoneNumber($phone); ?>" size="20" />
    </td>
</tr>
<?php } ?>  
<?php foreach($this->emails as $email) { $emailId = $email->id; ?>
<tr>
    <td>Email <?php echo $this->displayEmailType($email); ?></td>
    <td>
        <input type="hidden" name="email_ids[<?php echo $emailId; ?>]"       value="<?php echo $email->id; ?>" />
        <input type="hidden" name="email_type_ids[<?php echo $emailId; ?>]"  value="<?php echo $email->emailTypeId; ?>" />
        <input type="text"   name="email_addresses[<?php echo $emailId; ?>]" value="<?php echo $this->displayEmailAddress($email); ?>" size="40" />
    </td>
</tr>
<?php } ?>  
<tr>
    <td><?php echo $this->href('Person List','person_list'); ?></td>
    <td>
        <input type="checkbox" name="person_confirm_delete" value="1" />
        <input type="submit"   name="person_submit_delete"  value="Delete" />
        <input type="submit"   name="person_submit_clear"   value="Clear" />
        <input type="submit"   name="person_submit_create"  value="Create" />
        <input type="submit"   name="person_submit_update"  value="Update" />
    </td>
</tr>
</table>
<br />
<table border="1">
<tr><th colspan="6">Volunteer Positions <?php echo $this->displayPersonName($person); ?></th></tr>
<tr>
    <td>Region</td>
    <td>Year</td>
    <td>Season</td>
    <td>Position</td>
    <td>Division</td>
    <td>Notes</td>
</tr>
<?php foreach($this->vols as $vol) { $volId = $vol->id; ?>
<tr>
    <input type="hidden" name="vol_ids[<?php echo $volId; ?>]"  value="<?php echo $vol->id; ?>" />        
    <td>
        <select name="vol_unit_ids[<?php echo $volId; ?>]">
            <option value="0">NA</option>
            <?php echo $this->formOptions($this->unitPickList,$vol->unitId); ?>
        </select>
    </td> 
    <td>
        <select name="vol_year_ids[<?php echo $volId; ?>]">
            <option value="0">NA</option>
            <?php echo $this->formOptions($this->yearPickList,$vol->regYearId); ?>
        </select>
    </td>
    <td>
        <select name="vol_season_type_ids[<?php echo $volId; ?>]">
            <option value="0">NA</option>
            <?php echo $this->formOptions($this->seasonTypePickList,$vol->seasonTypeId); ?>
        </select>
     </td>
    <td>
        <select name="vol_type_ids[<?php echo $volId; ?>]">
            <option value="0">NA - Delete</option>
            <?php echo $this->formOptions($this->volTypePickList,$vol->volTypeId); ?>
        </select>
    </td>
    <td>
        <select name="vol_div_ids[<?php echo $volId; ?>]">
            <option value="0">NA</option>
            <?php echo $this->formOptions($this->divPickList,$vol->divisionId); ?>
        </select>
    </td>
    <td>
        <input type="text" name="vol_notes[<?php echo $volId; ?>]" value="<?php echo $this->escape($vol->note); ?>" size="30" />
    </td>
</tr>
<?php } ?>
<tr><?php $data = $this->personEditData; ?>
    <td>
        <select name="vol_show_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$data->volShowUnitId); ?>
        </select>
    </td> 
    <td>
        <select name="vol_show_year_id">
            <option value="0">All</option>
            <?php echo $this->formOptions($this->yearPickList,$data->volShowYearId); ?>
        </select>
    </td> 
    <td>
        <select name="vol_show_season_type_id">
            <option value="0">All</option>
            <?php echo $this->formOptions($this->seasonTypePickList,$data->volShowSeasonTypeId); ?>
        </select>
    </td> 
    <td colspan="2">
        <input type="submit" name="person_submit_update_vol_show" value="Show Different Positions" />    
    </td>
    <td><input type="submit" name="person_submit_update_positions" value="Update Positions" />
</tr>
</table>
</form>

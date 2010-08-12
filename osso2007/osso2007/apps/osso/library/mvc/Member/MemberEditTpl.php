<?php 
    $account = $this->accountItem; 
    $member  = $this->memberItem; 
?>
<form method="post" action="<?php echo $this->link('member_edit'); ?>"> 
<table border="1">
<tr><th colspan="2">Edit Account Information</th></tr>
<tr>
    <td>Account ID</td>
    <td><?php echo $account->id; ?></td>
    <input type="hidden" name="account_id"     value="<?php echo $account->id; ?>" />
    <input type="hidden" name="account_status" value="<?php echo $account->status; ?>" />
    <input type="hidden" name="member_id"      value="<?php echo $member->id;  ?>" />
</tr>
<tr>
    <td>Account User</td>
    <td><input type="text" name="account_user" size="40" value="<?php echo $account->user; ?>" /></td>
</tr>
<tr>
    <td>Account Name</td>
    <td><input type="text" name="account_name" size="40" value="<?php echo $account->name; ?>" /></td>
</tr>
<tr>
    <td>Account Email</td>
    <td><input type="text" name="account_email" size="40" value="<?php echo $account->email; ?>" /></td>
</tr>
<tr>
    <td>New Password 1</td>
    <td><input type="text" name="account_pass1" size="20" value="" /></td>
</tr>
<tr>
    <td>New Password 2</td>
    <td><input type="text" name="account_pass2" size="20" value="" /></td>
</tr>
<tr>
    <td><?php echo $this->href('Account Home','account_index'); ?></td>
    <td><input type="submit" name="account_submit_edit" value="Update Account Information" /></td>
</tr>
</table>
</form>
<br />
<form method="post" action="<?php echo $this->link('member_edit'); ?>"> 
<table border="1">
<tr>
    <th colspan="2">
        <?php 
            if ($member->id) echo "Edit Member Information";
            else             echo "Add New Member to Account";
        ?>
    </th>
</tr>
<tr>
    <td>Member ID</td>
    <td><?php echo $member->id; ?></td>
    <input type="hidden" name="account_id"    value="<?php echo $account->id; ?>" />
    <input type="hidden" name="member_id"     value="<?php echo $member->id;  ?>" />
    <input type="hidden" name="member_level"  value="<?php echo $member->level;  ?>" />
    <input type="hidden" name="member_status" value="<?php echo $member->status; ?>" />
</tr>
<tr>
    <td>Member Name</td>
    <td><input type="text" name="member_name" size="40" value="<?php echo $member->name; ?>" /></td>
</tr>
<tr>
    <td>Member Region</td.
    <td>
        <select name="member_unit_id">
            <option value="0">Select Region</option>
            <?php echo $this->formOptions($this->unitPickList,$member->unitId); ?>" />
        </select>
    </td>
</tr>
<tr>
    <td>AYSO ID</td>
    <td><input type="text" name="member_aysoid" size="10" value="<?php echo $member->aysoid; ?>" /></td>
</tr>
<tr>
    <td>Volunteer Name</td>
    <td><input type="text" name="volunteer_name" size="40" value="<?php echo $member->personName; ?>" readonly="true" /></td>
</tr>
<tr>
    <td><?php echo $this->href('Account Home','account_index'); ?></td>
    <td><?php echo $this->formUDC('member',$member->id); ?></td>
</tr>
</table>
</form>
<br />
<?php /* ?>
<form method="post" action="<?php echo $this->link('member_edit'); ?>">
<input type="hidden" name="account_id" value="<?php echo $account->id; ?>" />
<input type="hidden" name="member_id"  value="<?php echo $member->id;  ?>" />
<table border="1">
<tr><th colspan="4">Volunteer Search</th></tr>
<tr>
    <td>Last Name</td>
    <td>
        <input type="text" name="person_lname" size="20" 
            value="<?php echo $this->escape($this->personLastName); ?>" />
    </td>
    <td>Region</td>
    <td>
        <select name="person_unit_id">
            <option value="0">All Regions</option>
            <?php echo $this->formOptions($this->unitPickList,$this->personUnitId); ?>" />
        </select>
    </td>
</tr>
<tr>
    <td colspan="3">
    </td>
    <td><input type="submit" name="person_submit_search" value="Search" />
</tr>
</table>
</form>
<br />
<form method="post" action="<?php echo $this->link('member_edit'); ?>">
<input type="hidden" name="account_id" value="<?php echo $account->id; ?>" />
<input type="hidden" name="member_id"  value="<?php echo $member->id;  ?>" />
<table border="1">
<tr><th colspan="3">List of Volunteers</th></tr>
<tr>
    <td>Link</td>
    <td>Last Name</td>
    <td>First Name</td>
</tr>
<?php 
    foreach($this->personItems as $item) {
?>
<tr>
    <td><?php 
        if ($item->id == $member->personId) $selected = TRUE;
        else                                $selected = FALSE;
        echo $this->formRadioBox('person_link',$selected,$item->id); 
    ?></td>
    <td><?php echo $this->escape($item->lname);       ?></td>
    <td><?php echo $this->escape($item->fname); ?></td>
</tr>
<?php } ?>
<tr>
    <td colspan="3"><input type="submit" name="person_submit_link" value="Link Member to Volunteer" /></td>
</tr>
</table>
</form>
<?php */ ?>
<h3>Linking to AYSO Volunteer Record</h3>
<p>
If you are an AYSO volunteer (referee or coach) then you need to link your account to your
volunteer record.  Do this by entering your 8 digit aysoid number and then pressing update.
You can find your number by signing in to <a href="www.eayso.org">eayso</a>.
</p>
<?php /* ?>
<h3>Linking to AYSO Volunteer Record</h3>
<p>
If you are an AYSO volunteer (referee or coach) then you need to link your account to your
volunteer record.  The bottom table shows all the volunteers with your last name in your region.
Check the little circle next to your volunteer name then press the "Link Member to Volunteer" button.
And that should do it.
</p><p>
If you have a non-family member with a different last name in your account then type in their
last name in the "Volunteer Search Last Name" field and press the Search button.  Their record
should show up and can then be linked.
</p><p>
If you still can't see the volunteer record then that means they are not in the system.
Contact the <a href="mailto:ahundiak@ayso894.org">administrator</a> for help.
</p>
<?php */ ?>
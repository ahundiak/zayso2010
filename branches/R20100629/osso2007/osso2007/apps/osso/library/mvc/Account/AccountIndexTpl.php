<h3>User Home Page</h3>
<?php $data = $this->accountIndexData; ?>
<form method="post" action="<?php echo $this->link('account_index'); ?>"> 
<table border="1">
<tr>
    <td>
        <select name="sched_div_unit_id">
            <option value="0">Select Region</option>
            <?php echo $this->formOptions($this->unitPickList,$data->schedDivUnitId); ?>" />
        </select>
    </td>
    <td>
        <select name="sched_div_age_id">
            <option value="0">Select Age</option>
            <?php echo $this->formOptions($this->agePickList,$data->schedDivAgeId); ?>" />
        </select>
    </td>
    <td><input type="submit" name="sched_div_submit" value="Division Schedules" />
</tr>
</table></form>
<form method="post" action="<?php echo $this->link('account_index'); ?>"> 
<table border="1">
<tr>
    <td>
        <select name="sched_ref_unit_id">
            <option value="0">Select Region</option>
            <?php echo $this->formOptions($this->unitPickList,$data->schedRefUnitId); ?>" />
        </select>
    </td>
    <td>
        <select name="sched_ref_age_id">
            <option value="0">Select Age</option>
            <?php echo $this->formOptions($this->agePickList,$data->schedRefAgeId); ?>" />
        </select>
    </td>
    <td><input type="submit" name="sched_ref_submit" value="Referee Schedules" /></td>
</tr>
</table></form>
<br />
<?php echo $this->render('MemberListTpl'); ?>
<h3>Signing up to Referee</h3>
<p>
To sign up for a game, the system first needs to know that you are an ayso volunteer.
In the table above, look in the AYSO Name column.  
If you see your name there then the system knows you are a volunteer.
You can proceed to the referee schedules and start signing up.
</p><p>
If the AYSO Name column is blank then click on the Edit link next to your name.
Another screen will come up which will allow you to link to your AYSO volunteer record.
</p>
<h3>Family Accounts</h3>
<p>
Many families have more than one referee in them.  
The system allows you to have one family account containing multuple family (or friend) members.
This in turn allows you to signup all your family members for games without having to constantly
log in and out.</p>
<p>
To add a new family member to your account, click on the "add new member to account" link and follow
the instructions.  Be sure to link each new member to their ayso volunteer record so their
names will show up while signing up for games.
</p>

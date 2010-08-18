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
To sign up for a game, you must be certified as an AYSO referee in <a href="www.eayso.org">eAYSO</a>
Contact your Regional Referee Administrator if you don't see your certifications listed.
</p><p>
If the eAYSO information column says you are not linked then click on the Edit link.
Another screen will come up which will allow you to link to your AYSO volunteer record.
</p>
<h3>Family Accounts</h3>
<p>
Many families have more than one referee in them.  
The system allows you to have one family account containing multiple family (or friend) members.
This in turn allows you to sign up all your family members for games without having to constantly
log in and out.</p>
<p>
To add a new family member to your account, click on the "add new member to account" link and follow
the instructions.  Be sure to link each new member to their ayso volunteer record so their
names will show up while signing up for games.
</p>

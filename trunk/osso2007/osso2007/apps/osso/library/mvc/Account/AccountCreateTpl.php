<?php $data = $this->accountCreateData; ?>

<form method="post" action="<?php echo $this->link('account_create'); ?>"> 
<table border="1">
<tr>
    <th colspan="2">Create New Account</th>
</tr>
<tr>
    <td style="width: 180px;">User Login Name</td>
    <td><input type="text" name="account_user" size="40" value="<?php echo $data->accountUser; ?>" /></td>
</tr>
<tr>
    <td>Region</td>
    <td>
        <select name="member_unit_id">
            <option value="0">Region</option>
            <?php echo $this->formOptions($this->unitPickList,$data->memberUnitId); ?>" />
        </select>
    </td>
</tr>
<tr>
    <td>First Name</td>
    <td><input type="text" name="member_name" size="40" value="<?php echo $data->memberName; ?>" /></td>
</tr>
<tr>
    <td>Last Name</td>
    <td><input type="text" name="account_name" size="40" value="<?php echo $data->accountName; ?>" /></td>
</tr>
<tr>
    <td>Email</td>
    <td><input type="text" name="account_email" size="40" value="<?php echo $data->accountEmail; ?>" /></td>
</tr>
<tr>
    <td>Password 1</td>
    <td><input type="text" name="account_pass1" size="20" value="" /></td>
</tr>
<tr>
    <td>Password (Repeat)</td>
    <td><input type="text" name="account_pass2" size="20" value="" /></td>
</tr>
<tr>
    <td>AYSO ID (8 digits)*</td>
    <td><input type="text" name="member_aysoid" size="20" value="<?php echo $data->memberAysoid; ?>" /></td>
</tr>
<tr>
    <td></td>
    <td><input type="submit" name="account_submit_create" value="Create New Account" /></td>
</tr>
<?php if ($data->message) { ?>
<tr>
    <td colspan="2" style="color: red;"><?php echo $this->escape($data->message); ?></td>
</tr>
<?php } ?>
<tr>
    <td colspan="2">
      *Referees and coaches should enter their 8 digit AYSO ID number which can be found by signing in to
      <a href="www.eayso.org">EAYSO</a>.  You can enter this number later on the edit account page.
      Referees need to be certified in EAYSO before they can sign up for games.
    </td>
</tr>
</table>
</form>

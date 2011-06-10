<?php
  $data = $this->data;
  $user = $this->services->user;
?>
<div>
<form method="post" action="account-update">
<input type="hidden" name="account_update_id" value="<?php echo $this->escape($user->getAccountId()); ?>"/>
<table border="1" style="width: 700px;">
<tr><th colspan="3">S5Games Update Account<?php echo date('YmdHis'); ?></th></tr>
<tr>
  <td style="width: 125px;">Account Verified</td>
  <td style="width: 200px;">
    <input type="text" name="account_update_verified" size="20" readonly="readonly" value="<?php echo $this->escape($user->getVerified()); ?>"/>
  </td>
  <td style="width: 375px;">
    Set by tournament referee director <br />
  </td>
</tr>
<tr>
  <td style="width: 125px;">AYSOID (8 digits)</td>
  <td style="width: 200px;">
    <input type="text" name="account_update_aysoid" size="20" value="<?php echo $this->escape($user->getAysoid()); ?>"/>
  </td>
  <td style="width: 375px;">
    Required <br />
    8 digit AYSO id number. <br />
    Available from <a target="_blank" href="http://www.eayso.org">eAYSO</a>
  </td>
</tr>
<tr><td colspan="3"></td>
<tr>
  <td style="width: 125px;">Your First Name</td>
  <td style="width: 200px;">
    <input type="text" name="account_update_fname" size="20" value="<?php echo $this->escape($user->getAccountFirstName()); ?>"/>
  </td>
  <td style="width: 375px;"></td>
</tr>
<tr>
  <td style="width: 125px;">Your Last Name</td>
  <td style="width: 200px;">
    <input type="text" name="account_update_lname" size="20" value="<?php echo $this->escape($user->getAccountLastName()); ?>"/>
  </td>
  <td style="width: 375px;"></td>
</tr>
<tr>
  <td style="width: 125px;">Your Email</td>
  <td style="width: 200px;">
    <input type="text" name="account_update_email" size="40" value="<?php echo $this->escape($user->getAccountEmail()); ?>"/>
  </td>
  <td style="width: 375px;">
    Required<br />
    Needs to match your eAYSO email.
  </td>
</tr>
<tr>
  <td style="width: 125px;">Your Cell Phone</td>
  <td style="width: 200px;">
    <input type="text" name="account_update_phonec" size="20" value="<?php echo $this->escape($user->getAccountCellPhone()); ?>"/>
  </td>
  <td style="width: 375px;">
    Highly recommended.  <br />
    Some text messages may be sent.
  </td>
</tr>
<tr><td colspan="3"></td>
<tr>
  <td style="width: 125px;">Account Name</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_uname" size="40" value="<?php echo $this->escape($user->getAccountUserName()); ?>"/>
  </td>
  <td style="width: 375px;">
    Required <br />
    Used to sign in.  <br />
    Use your zayso account if you have one.
  </td>
</tr>
<tr>
  <td style="width: 125px;">Password</td>
  <td style="width: 200px;">
    <input type="password" name="account_update_upass1" size="20" value=""/>
  </td>
  <td style="width: 375px;">Required</td>
</tr>
<tr>
  <td style="width: 125px;">Password(repeat)</td>
  <td style="width: 375px;">
    <input type="password" name="account_update_upass2" size="20" value=""/>
  </td>
  <td style="width: 200px;">Required</td>
</tr>
<tr>
  <td>.</td>
  <td>.</td>
  <td align="center"><input type="submit" name="account_update_submit" value="Update Account" />
</tr>
<?php if (isset($data->errorsAccountUpdate) && count($data->errorsAccountUpdate)) { ?>
<tr><td colspan="3" style="color: red;">
<?php foreach($data->errorsAccountUpdate as $error) { ?>
<?php echo $this->escape($error) . '<br />' ?>
<?php } ?>
  </td></tr>
<?php } ?>
</table>
</form>
<br />
</div>
<?php
  $data = $this->data;
?>
<div>
  <a href="account-signin">Signin</a>
  <a href="account-create">Create</a>
</div>
<div>
<form method="post" action="account-create">
<table border="1" style="width: 700px;">
<tr><th colspan="3">S5Games Create Account <?php echo date('YmdHis'); ?></th></tr>
<tr>
  <td style="width: 125px;">AYSOID (8 digits)</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_aysoid" size="20" value="<?php echo $this->escape($data->aysoid); ?>"/>
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
    <input type="text" name="account_create_fname" size="20" value="<?php echo $this->escape($data->fname); ?>"/>
  </td>
  <td style="width: 375px;"></td>
</tr>
<tr>
  <td style="width: 125px;">Your Last Name</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_lname" size="20" value="<?php echo $this->escape($data->lname); ?>"/>
  </td>
  <td style="width: 375px;"></td>
</tr>
<tr>
  <td style="width: 125px;">Your Email</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_email" size="40" value="<?php echo $this->escape($data->email); ?>"/>
  </td>
  <td style="width: 375px;">
    Required<br />
    Needs to match your eAYSO email.
  </td>
</tr>
<tr>
  <td style="width: 125px;">Your Cell Phone</td>
  <td style="width: 200px;">
    <input type="text" name="account_create_phonec" size="20" value="<?php echo $this->escape($data->phonec); ?>"/>
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
    <input type="text" name="account_create_uname" size="40" value="<?php echo $this->escape($data->uname); ?>"/>
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
    <input type="password" name="account_create_upass1" size="20" value="<?php echo $this->escape($data->upass1); ?>"/>
  </td>
  <td style="width: 375px;">Required</td>
</tr>
<tr>
  <td style="width: 125px;">Password(repeat)</td>
  <td style="width: 375px;">
    <input type="password" name="account_create_upass2" size="20" value="<?php echo $this->escape($data->upass2); ?>"/>
  </td>
  <td style="width: 200px;">Required</td>
</tr>
<tr>
  <td>.</td>
  <td>.</td>
  <td align="center"><input type="submit" name="account_create_submit" value="Create Account" />
</tr>
<?php if (count($data->errors)) { ?>
<tr><td colspan="3" style="color: red;">
<?php foreach($data->errors as $error) { ?>
<?php echo $this->escape($error) . '<br />' ?>
<?php } ?>
  </td></tr>
<?php } ?>
</table>
</form>
<br />
</div>
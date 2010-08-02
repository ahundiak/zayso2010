<?php
  $html = $this->context->html;
  $user = $this->context->user;
  $accountData = $this->accountData;
?>
<form id="my-account-basic" method="post" action="my-account">
<table class="entry" style="width: 600px;">
<tr><th colspan="2">Basic Account Information</th></tr>
<tr>
  <td>Account User Name</td>
  <td>
    <input type="text" name="account_user_name" size="30" value="<?php echo $html->escape($accountData['account_user_name']); ?>" />
  </td>
</tr>
<tr>
  <td>Account Last Name</td>
  <td>
    <input type="text" name="account_lname" size="30" value="<?php echo $html->escape($accountData['account_lname']); ?>" />
  </td>
</tr>
<tr>
  <td>New Password</td>
  <td><input type="password" name="account_user_pass1" size="30" value="" /></td>
</tr>
<tr>
  <td>New Password(Repeat)</td>
  <td><input type="password" name="account_user_pass2" size="30" value="" /></td>
</tr>
<tr>
  <td>Password Recovery Hint</td>
  <td>
    <input type="text" name="account_hint" size="30" value="<?php echo $html->escape($accountData['account_hint']); ?>" />
  </td>
</tr>
<tr>
  <td>Password Recovery Email</td>
  <td>
    <input type="text" name="account_email" size="30" value="<?php echo $html->escape($accountData['account_email']); ?>" />
  </td>
</tr>
<tr>
  <td colspan="2" align="center"><input type="submit" name="account_basic_submit" value="Update" />
</tr>
</table>
</form>

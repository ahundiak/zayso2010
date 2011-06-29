<?php $data = $this->data; ?>
<div>
<form method="post" action="account-signin">
<input type="hidden" name="project_id" value="52" />
  <table class="entry" border="1" style="width: 500px;">
<tr><th colspan="2">National Games 2012 Sign In</th></tr>
<tr>
  <td style="width: 125px;">User Name</td>
  <td style="width: 300px;">
    <input type="text" name="signin_user_name" size="40" value="<?php echo $this->escape($data->userName); ?>"/>
  </td>
</tr>
<tr>
  <td style="width: 125px;">Password</td>
  <td style="width: 300px;">
    <input type="password" name="signin_user_pass" size="20" value=""/>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td align="center"><input type="submit" name="signin_submit" value="Sign In" />
</tr>
<?php if (count($data->errors)) { ?>
<tr><td colspan="2" style="color: red;">
<?php foreach($data->errors as $error) { ?>
<?php echo $this->escape($error) . '<br />' ?>
<?php } ?>
  </td></tr>
<?php } ?>
</table>
</form>
<br />
<h3>Help</h3>
<p>
  You must <a href="account-create">Create An Account</a> before you sign in.
  The password recovery stuff is not yet implemented so just <a href="contact">Contact Us</a> if you forgot your
  password or user name.
</p>
</div>
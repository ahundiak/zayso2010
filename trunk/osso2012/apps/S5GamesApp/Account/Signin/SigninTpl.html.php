<?php $data = $this->data; ?>
<div>
<form method="post" action="account-signin">
<table border="1" style="width: 500px;">
<tr><th colspan="2">S5Games Sign In <?php echo date('YmdHis'); ?></th></tr>
<tr>
  <td style="width: 125px;">User Name</td>
  <td style="width: 300px;">
    <input type="text" name="signin_user_name" size="40" value="<?php echo $this->escape($data->userName); ?>"/>
  </td>
</tr>
<tr>
  <td style="width: 125px;">Password</td>
  <td style="width: 300px;">
    <input type="text" name="signin_user_pass" size="20" value=""/>
  </td>
</tr>
<tr>
  <td>.</td>
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
</div>
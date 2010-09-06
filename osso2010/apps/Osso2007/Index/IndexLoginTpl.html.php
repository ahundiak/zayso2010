<?php $data = $this->homeLoginData; ?>
<form method="post" action="<?php echo $this->link('home_login'); ?>"> 
<table border="1">
<tr><th colspan="2">User Login</th></tr>
<tr>
  <td>Account Name</td>
  <td>
    <input type="text" name="login_account_user" value="" size="30" />
  </td>
</tr>
<tr>
  <td>Account Password</td>
  <td>
    <input type="password" name="login_account_pass" value="" size="30" />
  </td>
</tr>
<!-- 
<tr>
    <td>Family Member Name</td>
    <td>
        <input type="text" name="login_member_name" value="" size="30" />
    </td>
</tr>
-->
<tr>
  <td></td>
  <td><input type="submit" name="login_submit_login"  value="Log In" /></td>
</table>
</form>
<hr />
<p>
  Zayso now allows you to link your account to one or more social networking accounts.
  After doing so you can log in to Zayso by clicking on one of the icons below.
  No need to remember your Zayso login and password.
</p><p>
  <span style="font-weight:bold;"> Use the form above to sign in the usual way.</span>
</p><p>
  Once you have signed in, follow the instructions at the bottom of the home page to link Zayso to a socal networking site.
  The icons below will not work until your account is linked.
</p>
<?php
  $url = $this->context->request->webBase . 'index_login_rpx';
?>
<iframe
  src="http://zayso.rpxnow.com/openid/embed?token_url=<?php echo $url; ?>"
  scrolling="no"  frameBorder="no"  allowtransparency="true"  style="width:400px;height:240px">
</iframe>

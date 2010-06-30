<?php $data = $this->homeLoginData; ?>
<p><span style="color: red">IMPORTANT: </span>
The global password has been disabled.  You must use your own password to login.
Contact your referee administrator if you are having trouble.
</p>
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

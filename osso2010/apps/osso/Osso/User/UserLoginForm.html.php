<?php
  $accountData = $this->context->session->get('account-login');
  if (!$accountData) $accountName = $this->context->user->accountName;
  else
  {
    $accountName = $accountData['account_name'];
  }

  $html = $this->context->html;

?>
<table class="entry" style="width: 600px;">
<form id="account-login-form" method="post" action="action/user-login">
<tr><th colspan="2">User Login</th></tr>
<tr>
  <td>User Name</td>
  <td>
    <input type="text" name="account_name" size="30" value="" />
  </td>
</tr>
<tr>
  <td>Password</td>
  <td><input type="password" name="account_pass" size="30" /></td>
</tr>
<?php if (isset($accountData['msg'])) { ?>
<tr>
  <td>Error Message</td>
  <td style="color: red;"><?php echo $html->escape($accountData['msg']); ?></td>
</tr>
<?php } ?>
<?php if (isset($accountData['hint']) && $accountData['hint']) { ?>
<tr>
  <td>Password Hint</td>
  <td><?php echo $html->escape($accountData['hint']); ?></td>
</tr>
<?php } ?>
<?php if (isset($accountData['email']) && $accountData['email']) { ?>
<tr>
  <td>Account Email</td>
  <td>
    <?php echo $html->escape($accountData['email']); ?>
    <input type="submit" name="account_reset_submit" value="Email password reset request" />
  </td>
</tr>
<?php } ?>
<tr>
  <td colspan="2" align="center"><input type="submit" name="account_login_submit" value="Log In" />
</tr>
</form>
</table>

<?php if ($accountData) { ?>
<form id="account-help" method="post" action="account-help">
  <table class="entry" width="600px">
    <tr><th colspan="2">Request for help with logging on</th></tr>
    <tr>
      <td>Your email</td>
      <td><input type="text" name="account_help_email" size="40"/></td>
    </tr>
    <tr>
      <td>Your name and region</td>
      <td><input type="text" name="account_help_name" size="40"/></td>
    </tr>
    <tr>
      <td>Additional information</td>
      <td><input type="text" name="account_help_info" size="60"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" name="account_help_submit" value="Send Help Request" />
    </tr>
  </table>
</form>
<?php } ?>
<script type="text/javascript">

  // Default values
  $.ajax(
  {
    cache:    false,
    datatype: 'json',
    url:      'action/user-login-data',
    success: function(result)
    {
      var data = result.data || {};
      if (data.account_name) $('#account-login-form [name="account_name"]').attr('value',data.account_name);
    }
  });

  // ajax processing
  $('#account-login-form').ajaxForm(
  {
    success: function(response)
    {
      if (response.success)
      {
        // Remove the Welcome tab, Add in Home tab as well as additional tabs
        $('body').trigger('userChanged')
      }
      else alert(response.msg);
      //console.log(response.msg);
    },
    dataType: 'json'
  });
</script>

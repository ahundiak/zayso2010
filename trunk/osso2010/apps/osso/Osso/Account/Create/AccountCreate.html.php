<div  id="account-create-div">
<form id="account-create-form" action="action/account-create" method="post">
<table class="entry" style="width: 600px;">
  <tr><th colspan="2">Create Account</th></tr>
  <tr>
    <td class="label">User Name</td>
    <td class="input"><input type="text" name="user_name" size="30" /></td>
  </tr>
  <tr>
    <td class="label">Password</td>
    <td class="input"><input type="password" name="user_pass1" size="30" /></td>
  </tr>
  <tr>
    <td class="label">Password(Repeat)</td>
    <td class="input"><input type="password" name="user_pass2" size="30" /></td>
  </tr>
  <tr>
    <td class="label">Password Hint</td>
    <td class="input"><input type="text" name="user_pass_hint" size="30" /></td>
  </tr>
  <tr>
    <td class="label">First Name</td>
    <td class="input"><input type="text" name="user_fname" size="20" /></td>
  </tr>
  <tr>
    <td class="label">Last Name</td>
    <td class="input"><input type="text" name="user_lname" size="20" /></td>
  </tr>
  <tr>
    <td class="label">Email</td>
    <td class="input"><input type="text" name="user_email" size="30" /></td>
  </tr>
  <tr>
    <td class="label">AYSO Region</td>
    <td class="input"><input type="text" name="user_region" size="10" /></td>
  </tr>
  <tr>
    <td class="label">AYSO Volunteer ID</td>
    <td class="input"><input type="text" name="user_aysoid" size="10" /></td>
  </tr>
  <tr>
    <td class="label">AYSO Player ID</td>
    <td class="input"><input type="text" name="user_playerid" size="10" /></td>
  </tr>
   <tr>
    <td colspan="2" align="center">
      <input type="submit" name="account_create_submit" value="Create" />
    </td>
  </tr>
</table>
</form>
</div>

<script type="text/javascript">

  $('#account-create-form').ajaxForm(
  {
    success: function(response)
    {
      if (response.success)
      {
        alert(response.msg);
      }
      else
      {
        alert(response.errors);
      }
    },
    dataType: 'json'
  });
  
</script>

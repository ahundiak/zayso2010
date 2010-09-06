<form method="post" action="account_openid_link">
<table border="1">
  <tr><th colspan="3">Currently Linked Accounts</th></tr>
  <tr>
    <td style="width: 100px;">Provider</td>
    <td style="width: 150px;">Display Name</td>
    <td style="width: 100px;">Delete</td>
  </tr>
  <?php foreach($this->openids AS $openid) { ?>
  <tr>
    <td><?php echo $this->escape($openid['provider']); ?></td>
    <td><?php echo $this->escape($openid['display_name']); ?></td>
    <td>
      <input type="checkbox" name = "openid_delete_ids[<?php echo $openid['id']; ?>]" value="<?php echo $openid['id']; ?>" />
    </td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="2">Check to box next to the delete button to confirm delete</td>
    <td>
      <input type="checkbox" name="openid_confirm_delete" value="1" />
      <input type="submit"   name="openid_submit_delete"  value="Delete" />
    </td>
</tr>
</table></form>
<br />
<p>
Click on the icon(below) of the site you wish to link to Zayso.  You can link multiple sites if you wish.
Press the "use another account" button to see all available account types.
Zayso never sees the password of the social networking site.
You will always be redirected to the social site itself before being asked for a password.
You should also be aware that some sites indicate that Zayso can update your information.
Zayso does not in fact have the ability to update any information on the social sites.
</p>
<br />
<p>
If you do not wish to use one of the common sites then use the myOpenID icon.
This is a very generic account management site with the usual password recovery features.
<a href="http://www.myopenid.com/">Click here to create an account on myOpenID</a> then link it to Zayso.
</p>
<?php
  $url = $this->context->request->webBase . 'account_openid_link_rpx';
?>
<iframe
  src="http://zayso.rpxnow.com/openid/embed?token_url=<?php echo $url; ?>"
  scrolling="no"  frameBorder="no"  allowtransparency="true"  style="width:400px;height:240px">
</iframe>

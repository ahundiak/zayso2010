<div id="welcome-div">
  <div class="content">
    <p>Welcome Content</p>
    <p>Why did they make the margins so wide and the fonts so big???</p>
    <a id="account-create-link" href="">Create New Account</a>
  </div>
  <?php echo $this->render('Osso/User/UserLoginForm.html.php'); ?>
</div>
<script type="text/javascript">
$(function()
{
  var tabId = '#tabs';
  var accountCreateIndex = -1;

  $('#account-create-link').click(function()
  {
    if (accountCreateIndex < 0)
    {
      $(tabId).tabs('add','tab/account-create','Create Account');
      accountCreateIndex = $(tabId).tabs('length') - 1;
    }
    $(tabId).tabs('select',accountCreateIndex);

    return false;
  });
});
</script>
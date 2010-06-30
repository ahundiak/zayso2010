<?php
?>
<h1>Welcome to the Section 5 Games 2010 Schedule Website</h1>
<h3>User sign on area</h3>
<div id="welcome-usersignin">Replace with form</div>
<script type="text/javascript">

function welcomeUserSignIn()
{
  console.log('Welcome loaded and executed');
  var form = new Zayso.User.SignIn.Form();
  form.load();
  form.render('welcome-usersignin');

}
welcomeUserSignIn();
</script>
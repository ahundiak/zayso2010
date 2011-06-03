<?php ?>
<div>
  <h3>Welcome</h3>
  <p>
    Welcome to the S5Games 2011 Referee Scheduling Site.
    This site will allow you to sign up to referee specific games during the tournament.
  </p><p>
    It's very similar to the one we have used for the past two years.
    The main difference is that you must <a href="account-create">create yourself an account</a> before you can sign up.
  </p><p>
    If you already have an account then sign in below.
    No account is needed to <a href="schedule-show">view the schedules.</a>
</div>
<?php echo $this->render('S5GamesApp/Account/Signin/SigninTpl.html.php'); ?>
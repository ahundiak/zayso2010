<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<?php $user = $this->services->user; ?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <link rel="stylesheet" type="text/css" href="../css/osso2012.css" />

  <title><?php echo $this->tplTitle; ?></title>
  <base href="<?php echo $this->services->request->webBase; ?>" />
</head>
<body>
  <div>
<div id="layout-header">
  <a class="a-target-blank" href="http://soccer.org/events/national_games.aspx" target="_blank">
    <img class="logo1" src="../images/NatGamesLogo.jpg" height="50" width="50" alt="National Games Site"/>
  </a>
  <a class="a-target-blank" href="http://www.soccer.org" target="_blank">
    <img class="logo2" src="../images/AysoLogo.gif"     height="50" width="50" alt="AYSO Site"/>
  </a>
  <div class="inner-block">
    <p>
      <span style="color: blue;">zAYSO</span>
      <span style="color: red">National Games 2012 Referee Scheduling Site</span>
    </p>
    <p style="margin-top: 2px; color: black; ">User: <?php echo $user->getName(); ?></p>
  </div>
</div>
  <?php ?>
<div id="layout-top-menu">
  <ul style="float:left ">
    <li><a href="welcome">Welcome</a></li>
    <?php
      if ($user->isSignedIn()) echo '<li><a href="home">Home</a></li>' . "\n";
    ?>
    <?php
      if (!$user->isSignedIn()) echo '<li><a href="account-create">Create Account</a></li>' . "\n";
    ?>
  </ul>
  <ul style="float: right">
    <li><a href="contact">Contact Us</a></li>
    <?php 
      if ($user->isSignedIn()) echo '<li><a href="account-signout">Sign Out</a></li>' . "\n";
      else                     echo '<li><a href="account-signin">Sign In</a></li>' . "\n";
    ?>
  </ul>
  <div class="inner"></div>
</div>
<div id="layout-content">
  <?php echo $this->content; ?>
</div>
<?php    ?>
  </div>
</body>
</html>

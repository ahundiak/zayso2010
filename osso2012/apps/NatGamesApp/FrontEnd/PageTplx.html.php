<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title><?php echo $this->tplTitle; ?></title>
  <base href="<?php echo $this->services->request->webBase; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="natgames.css" />
</head>
<body id="layout-body">
<div  id="layout-page">
  
<div id="layout-banner">
  <div>
    <?php 
      $user = $this->services->user;
      if ($user->isGuest()) $userInfo = 'Guest';
      else
      {
        $userInfo = sprintf('%s, %s, %s, %s, MY%s, %s, %s',
                $user->getAccountUserName(),
                $user->getName(),
                $user->getRegion(),
                $user->getAysoid(),
                $user->getRegYear(),
                $user->getRefereeBadgeDesc(),
                $user->getSafeHavenDesc()
        );
      }
    ?>
    User: <?php echo $userInfo; ?>
  </div>
  <div id="layout-menu-top">
  <div>
    <a href="welcome">Welcome</a>

    <?php if ($user->isSignedIn()) { ?>
      <a href="home">Home</a>
    <?php } ?>
    
    <a href="schedule-show">Schedules</a>
    <a href="schedule-stats">Stats</a>

    <?php if ($user->isAdmin() || 1) { ?>
      <a href="account-list">Accounts</a>
      <a href="session-show">Sessions</a>
      <a href="admin-clear">Clear Cookies</a>
    <?php } ?>
    <?php if ($user->isSignedIn()) { ?>
      <a href="account-signout">Sign Out</a>
   <?php } else { ?>
    <a href="account-signin">Sign In</a>
    <?php } ?>
  </div>
</div>
<div id="layout-content">
<?php echo $this->content; ?>
</div>
</div>
</div>
</body>
</html>

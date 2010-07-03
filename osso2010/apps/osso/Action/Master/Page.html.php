<?php
  $topMenuItems = array
  (
    array('label' => 'Home',              'href' => '?'),
    array('label' => 'My Account',        'href' => '?la=my-account'),
    array('label' => 'My Schedule',       'href' => '?la=my-schedule'),
    array('label' => 'Team Schedules',    'href' => '?la=team-schedule'),
    array('label' => 'Referee Schedules', 'href' => '?la=referee-schedule'),
    array('label' => 'Referee Points',    'href' => '?la=referee-points'),
    array('label' => 'Admin',             'href' => '?la=admin'),
  );
?>
<html>
  <head>
    <title><?php echo $this->tplTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="?lt=css&f=osso" />
  </head>
  <body id="layout-body">
    <div id="layout-page">
      <div id="layout-banner">
        <div id="layout-banner-left">
          <span class="bold">Zayso 2010</span><span>User Information</span>
        </div>
        <div id="layout-banner-right">
          <span><a href="?la=account-logout">Log Out</a></span>
        </div>
      </div>
      <div id="layout-menu-top">
        <ul>
          <?php foreach($topMenuItems as $item) { ?>
          <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <div id="layout-content">
        <?php echo $this->tplContent; ?>
      </div>
    </div>
  </body>
</html>

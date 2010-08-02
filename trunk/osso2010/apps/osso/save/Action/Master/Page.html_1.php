<?php
$user = $this->context->user;

// User info
$info1 = $user->info1;
$info2 = $user->info2;

if (!$user->isLoggedIn) $info1 .= ' (Not logged in)';

$url = $this->genUrl();

// Always display these
$topMenuItems = array
(
  array('label' => 'Welcome',      'href' => '#welcome'), // $this->genUrl()),
  array('label' => 'Field Status', 'href' => $this->genUrl('field-status')),
  array('label' => 'Training',     'href' => $this->genUrl('training')),
);

// Menus to display
if ($user->isGuest || !$user->isLoggedIn) {}
else
{
  // Additional
  $topMenuItems = array_merge($topMenuItems,array
  (   
    array('label' => 'My Account',  'href' => 'my-account'),
    array('label' => 'My Schedule', 'href' => $this->genUrl('my-schedule')),
      
    array('label' => 'Schedules',   'href' => $this->genUrl('schedule')),
  ));
  if ($user->isReferee)
  {
    $topMenuItems[] = array('label' => 'Referee Schedules', 'href' => $this->genUrl('referee-schedule'));
    $topMenuItems[] = array('label' => 'Referee Points',    'href' => $this->genUrl('referee-points'));
  }
  if ($user->isAdmin)
  {
    $topMenuItems[] = array('label' => 'Admin','href' => $this->genUrl('admin'));
    
  }
}
// href="?lt=css&f=osso"
// $this->genUrl('person-edit',array('id'=>27)
// $this->context->http->loadCSS('osso')
?>
<html>
  <head>
    <title><?php echo $this->tplTitle; ?></title>
    <base href="/osso2010/index.php" />

    <link rel="stylesheet" type="text/css" href="css/osso" />
    <link rel="stylesheet" type="text/css" href="../tools/jquery/css/smoothness/jquery-ui-1.8.2.custom.css">

    <script type="text/javascript" src="../tools/jquery/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../tools/jquery/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript">
      $(function()
      {
        $('#tabs').tabs();
      });
    </script>

  </head>
  <body id="layout-body">
    <div id="layout-page">
      <div id="layout-banner">
        <div id="layout-banner-left">
          <div style="width: 150px; float: left;">
            <span class="bold">Zayso 2010</span>
          </div>
          <div>
            <span style="margin-left: 5px;"><?php echo $info1; ?></span><br />
            <span style="margin-left: 5px;"><?php echo $info2; ?></span>
          </div>
        </div>
        <div id="layout-banner-right">
          <span><a href="account-logout">Contact</a></span>
          <span><a href="account-logout">Help</a></span>
          <span style="margin-right: 5px;"><a href="account-logout">Log Out</a></span>
        </div>
      </div>
      <div id="layout-menu-top">
        <ul>
          <?php foreach($topMenuItems as $item) { ?>
          <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <div id="tabs">
        <ul style="font-size: 0.4em;">
          <?php foreach($topMenuItems as $item) { ?>
            <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <div id="welcome">
        <?php echo $this->tplContent; ?>
      </div>
    </div>
  </body>
</html>

<html>
  <head>
    <title><?php echo $this->tplTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="?lt=css&f=osso" />
  </head>
  <body id="layout-body">
    <div id="layout-page">
      <div id="layout-banner">
        <div id="layout-banner-left">
          <div style="width: 150px; float: left;">
            <span class="bold">Zayso 2010</span>
          </div>
          <div>
            <span style="margin-left: 5px;">Guest</span><br />
            <span style="margin-left: 5px;">Welcome to the AYSO Area 5C Scheduling System</span>
          </div>
        </div>
        <div id="layout-banner-right">
          <span><a href="?la=account-logout">Contact</a></span>
          <span><a href="?la=account-logout">Help</a></span>
          <span style="margin-right: 5px;"><a href="?la=account-logout">Log In</a></span>
        </div>
      </div>
      <div id="layout-content">
      <?php echo $this->tplContent; ?>
      </div>
    </div>
  </body>
</html>

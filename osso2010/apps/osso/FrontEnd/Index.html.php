<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
  <head>
    <title>OSSO 2010a</title>
    <base href="/osso2010/index.php" />
    
<!-- <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script> -->

    <link rel="stylesheet" type="text/css" href="../tools/jquery/css/smoothness/jquery-ui-1.8.2.custom.css" />
    <link rel="stylesheet" type="text/css" href="css/osso" />

    <script type="text/javascript" src="../tools/jquery/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../tools/jquery/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../tools/jquery/js/jquery.form.js"></script>
    <script type="text/javascript" src="js/osso"></script>

    <script type="text/javascript">
      $(function()
      {
        // Handle user logging in
        $('body').bind('userChanged',function()
        {
          // alert('User has loggin in');
          osso.updateBanner();
          osso.updateMenu();
        });
        $('body').trigger('userChanged');

        // Handle user logging out
        $('#user-logout-link').bind('click',function()
        {
          $.ajax(
          {
            cache:    false,
            datatype: 'json',
            url:      'action/user-logout',
            success: function()
            {
              $('body').trigger('userChanged');
            }
          });
          return false;
        });
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
          <div id="layout-banner-user-info"></div>
        </div>
        <div id="layout-banner-right">
          <span><a href="account-logout">Contact</a></span>
          <span><a href="account-logout">Help</a></span>
          <span style="margin-right: 5px;"><a id="user-logout-link" href="action/user-logout">Log Out</a></span>
        </div>
      </div>
      <div id="tabs"><ul></ul></div>
    </div>
  </body>
</html>

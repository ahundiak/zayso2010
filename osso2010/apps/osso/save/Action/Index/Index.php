<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
  <head>
    <title>OSSO 2010</title>
    <base href="/test9/index.php" />
    
<!-- <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script> -->

    <link rel="stylesheet" type="text/css" href="../tools/jquery/css/smoothness/jquery-ui-1.8.2.custom.css">
    <link rel="stylesheet" type="text/css" href="css/osso">

    <script type="text/javascript" src="../tools/jquery/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../tools/jquery/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../tools/jquery/js/jquery.form.js"></script>

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
            url:      'actionx/user-logout',
            success: function()
            {
              $('body').trigger('userChanged');
            }
          });
          return false;
        });
      });

      // osso namespace
      window.osso = {};

      osso.updateMenu = function()
      {
        // Get latest user information
        $.ajax(
        {
          cache:    false,
          datatype: 'json',
          url:      'actionx/user-menu',
          success: function(result)
          {
            var items = result.items;
            var tabs$ = $('#tabs');
            tabs$.tabs('destroy');
            tabs$.tabs(
            { cache: true,
              ajaxOptions: { cache: false }
            });
            for(i = 0; i < items.length; i++)
            {
                var item = items[i];
                // alert('Item ' + item.url + ' ' + item.label);
                tabs$.tabs('add',item.url,item.label);
            }
            tabs$.tabs('select',0);
          }
        });
      }
      osso.updateBanner = function()
      {
        // Get latest user information
        $.ajax(
        {
          cache:    false,
          datatype: 'json',
          url:      'actionx/user-info',
          success: function(result)
          {
            // alert(result.user.info1);
            var tpl =
              '<span style="margin-left: 5px;">' + result.user.info1 + '</span><br /> ' +
              '<span style="margin-left: 5px;">' + result.user.info2 + '</span>';
            $('#layout-banner-user-info').html(tpl);
          }
        });
      }
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
          <span style="margin-right: 5px;"><a id="user-logout-link" href="actions/user-logout">Log Out</a></span>
        </div>
      </div>
      <div id="tabs"><ul></ul></div>
        <?php /*
          <?php foreach($topMenuItems as $item) { ?>
            <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
          <?php } ?>
        </ul>
        <div id="field-status">Field Status Content</div>
        <div id="training">Training Content</div>
      </div> */ ?>
    </div>
  </body>
</html>

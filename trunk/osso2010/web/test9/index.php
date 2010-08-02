<?php

// Always display these
$topMenuItems = array
(
  array('label' => 'Welcome',      'href' => 'welcome.html'),
  array('label' => 'Field Status', 'href' => '#field-status'),
  array('label' => 'Training',     'href' => '#training'),
);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
  <head>
    <title>JQuery Test 9</title>
    <base href="/test9/index.php" />
    
<!-- <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script> -->

    <link rel="stylesheet" type="text/css" href="../tools/jquery/css/smoothness/jquery-ui-1.8.2.custom.css">
    <link rel="stylesheet" type="text/css" href="../tools/jquery/css/jqtransform.css">
    <link rel="stylesheet" type="text/css" href="style.css">

    <script type="text/javascript" src="../tools/jquery/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../tools/jquery/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../tools/jquery/js/jquery.form.js"></script>
    <script type="text/javascript" src="../tools/jquery/js/jquery.jqtransform.js"></script>

    <script type="text/javascript">
      $(function()
      {
        var tabId = '#tabs';
        $(tabId).tabs(
        { cache: true,
          ajaxOptions: { cache: false }
        });

        $('<div>').dialog(
        {
          autoOpen: false,
          width: 600,
          open: function(){ $(this).load('account.create.html'); },
          title: 'Create Account'
        });
      });
    </script>

  </head>
  <body id="layout-body">
    <div id="layout-page">
      <div id="layout-banner">
      </div>
      <div id="tabs">
        <ul style="font-size: 0.4em;">
          <?php foreach($topMenuItems as $item) { ?>
            <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
          <?php } ?>
        </ul>
        <div id="field-status">Field Status Content</div>
        <div id="training">Training Content</div>
      </div>
    </div>
  </body>
</html>

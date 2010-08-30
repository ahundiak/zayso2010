<?php
  $extDir = '../tools/ext/';
  $title  = 'ExtJS Data Test';
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title><?php echo $title; ?></title>
    
    <link rel="stylesheet" type="text/css" href="<?php echo $extDir; ?>resources/css/ext-all.css" />
    <script type="text/javascript" src="<?php echo $extDir; ?>adapter/ext/ext-base.js"> </script>
    <script type="text/javascript" src="<?php echo $extDir; ?>ext-all-debug.js"> </script>

    <script type="text/javascript" src="data.js"></script>
    <!--
    <script type="text/javascript" src="VolGrid.js"></script>
    -->
</head>
<body>
  <p>Got something</p>
  <div id="replace" >Replace Me</div>
</body>
</html>

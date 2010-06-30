<?php
  $toolsDir = $this->context->config['web_tools'];
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title><?php echo $this->title; ?></title>
    <link type="text/css" href="<?php echo $toolsDir; ?>jquery/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo $toolsDir; ?>jquery/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $toolsDir; ?>jquery/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo $toolsDir; ?>jquery/js/jquery.json-2.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $toolsDir; ?>jquery/js/ajaxupload.js"></script>

    <?php foreach($this->jsFiles as $file) { ?>
    <script type="text/javascript" src="?lt=js&f=<?php echo $file; ?>"></script>
    <?php } ?>
    
  </head>
  <body>
    <?php echo $this->content; ?>
  </body>
</html>

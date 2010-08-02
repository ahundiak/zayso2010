<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Cache-Control" content="no-cache" />
  
  <title><?php echo $title; ?></title>
<?php if ($loadFirebug) { ?>
  <script type="text/javascript" src="<?php echo $baseFirebugPath; ?>firebug-lite-compressed.js"></script>
<?php } ?>
  <link rel="stylesheet" type="text/css" href="<?php echo $baseExtJSPath; ?>resources/css/ext-all.css" />
  
  <script type="text/javascript" src="<?php echo $baseExtJSPath; ?>adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="<?php echo $baseExtJSPath; ?>ext-all-debug.js"></script>
  
  <script type="/text/javascript">
    Ext.BLANK_IMAGE_URL = '<?php echo $baseExtJSPath; ?>resources/images/default/s.gif';
  </script>

  <script type="text/javascript" src="<?php echo $baseAppPath; ?>jsload.php"></script>

<?php echo $js; ?>

</head>
<body></body>
</html>


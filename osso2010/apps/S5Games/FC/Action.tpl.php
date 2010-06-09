<?php
$baseAppPath     = '';  // Until we rediscover a need for a hard coded path
$baseExtJSPath   = '../tools/ext/';
$baseFirebugPath = '../tools/firebug-lite/';
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Cache-Control" content="no-cache" />
  
  <title><?php echo $title; ?></title>
<?php if ($loadFirebug && 0) { ?>
  <script type="text/javascript" src="<?php echo $baseFirebugPath; ?>build/firebug-lite.js"></script>

<?php } ?>
  <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo $baseExtJSPath; ?>resources/css/ext-all.css" />
  
  <script type="text/javascript" src="<?php echo $baseExtJSPath; ?>adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="<?php echo $baseExtJSPath; ?>ext-all-debug.js"></script>
  
  <script type="text/javascript">
    Ext.BLANK_IMAGE_URL = '<?php echo $baseExtJSPath; ?>resources/images/default/s.gif';
  </script>

<?php 
  // Load the java script files
  if ($loadJSFilesIndividually)
  { 
    foreach($jsFiles as $file)
    {
      $file = str_replace('/','-',$file);
      echo "<script type=\"text/javascript\" src=\"{$baseAppPath}index.php?jsload={$file}\"></script>\n";
    }
  }
  else 
  {
    echo "<script type=\"text/javascript\" src=\"{$baseAppPath}index.php?jsload\"></script>\n";
  } 
?>
<?php 
  // Load the action specific files
  foreach($jsFilesx as $file)
  {
    $file = str_replace('/','-',$file);
    echo "<script type=\"text/javascript\" src=\"{$baseAppPath}index.php?jsload={$file}\"></script>\n";
  }
?>
<?php 
  // The previously loaded js startup code
  echo $js; 
?>
</head>
<body></body>
</html>
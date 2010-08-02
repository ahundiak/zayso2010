<?php
  $toolsDir = $this->context->config['web_tools'];
  $upDir = $toolsDir . 'jquery/jquery.uploadify-v2.1.0/';
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title><?php echo $this->title; ?></title>
    <script type="text/javascript" src="<?php echo $upDir; ?>jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $upDir; ?>swfobject.js"></script>
    <script type="text/javascript" src="<?php echo $upDir; ?>jquery.uploadify.v2.1.0.js"></script>

    <script type="text/javascript">
     jQuery(document).ready(function()
     {
       $('#fileInput1').uploadify({
        'uploader'    : '<?php echo $upDir; ?>uploadify.swf',
        'script'      : '<?php echo $upDir; ?>uploadifyx.php',
//      'checkScript' : '<?php echo $upDir; ?>check.php',
        'cancelImg'   : '<?php echo $upDir; ?>cancel.png',
        'auto'        : false,
        'multi'       : false,
        'folder'      : 'uploads'
      });
     });
    </script>

  </head>
  <body>
    <div>
      <p><strong>Single File Upload</strong></p>
      <input id="fileInput1" name="fileInput1" type="file" />
      <a href="javascript:$('#fileInput1').uploadifyUpload();">Upload Files</a>
    </div>
  </body>
</html>

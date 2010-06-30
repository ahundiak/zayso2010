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

    <script type="text/javascript" src="?lt=js&f=jquery-upload"></script>


  </head>
  <body>
<ul>
  <li id="example1" class="example">
    <p>You can style button as you want</p>
    <div class="wrapper">
      <div id="button12" class="button">Upload</div>
      <input type="button" id="button1" value="Me" />
    </div>
    <p>Uploaded files:</p>

    <ol class="files"></ol>
  </li>

  <li id="example2" class="example">
    <p>You can style button as you want</p>
    <form id="form2" action="post">
      <input type="text"   class="text2"   name="filename" />
      <input type="button" class="button2" value="Browse" />
      <input type="button" class="submit2" value="Submit" />
    </form>
    <p>Uploaded files:</p>

    <ol class="files"></ol>
  </li>

</ul>

  </body>
</html>

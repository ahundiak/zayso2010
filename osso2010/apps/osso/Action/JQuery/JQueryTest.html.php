<?php
  $toolsDir = $this->context->config['web_tools'];
  $upDir = $toolsDir . 'jquery/jquery.uploadify-v2.1.0/';
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title><?php echo $this->title; ?></title>
    <link type="text/css" href="<?php echo $toolsDir; ?>jquery/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo $toolsDir; ?>jquery/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $toolsDir; ?>jquery/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo $toolsDir; ?>jquery/js/jquery.json-2.2.min"></script>
    <script type="text/javascript" src="<?php echo $upDir; ?>swfobject.js"></script>
    <script type="text/javascript" src="<?php echo $upDir; ?>jquery.uploadify.v2.1.0.js"></script>

    <script type="text/javascript">
     jQuery(document).ready(function()
     {
       jQuery('#date1').datepicker();
       jQuery('#date2').datepicker();

       jQuery('aa').click(function(event){
         alert("As you can see, the link no longer took you to jquery.com");
         event.preventDefault();
       });
       
       jQuery('#button').click(function(event)
       {
         event.preventDefault();

         var params = jQuery("#form").serializeArray();
         var paramsx = {};
         jQuery.each( params, function()
         {
          console.log( this.name + '=' + this.value );
          paramsx[this.name] = this.value;
	 });
         console.log(paramsx);
         // alert("Post pressed");
         var params = jQuery.toJSON(jQuery("#form").serializeArray());
         var params = jQuery.toJSON(paramsx);
         
         //var params = jQuery.toJSON(jQuery("#form"));
         var request = {
           action: 'action',
           method: 'method',
           params: params
         };
         jQuery.post("test.php", request, function(results)
         {
           alert('Results: ' + results.msg);
         },'json');
       });
       $('#fileInput1').uploadify({
        'uploader'    : '<?php echo $upDir; ?>uploadify.swf',
        'script'      : '<?php echo $upDir; ?>uploadify.php',
        'checkScript' : '<?php echo $upDir; ?>check.php',
        'cancelImg'   : '<?php echo $upDir; ?>cancel.png',
        'auto'        : false,
        'folder'      : 'uploads'
      });
     });
    </script>

  </head>
  <body id="layout-body">
    <p><a href="http://jquery.com/">jQuery</a></p>

    <form id="form" name="form">
      <input type="text" name="date1" id="date1" />
      <input type="text" name="date2" id="date2" />
      <input type="button" name="submit" id="button" value="Post" />
    </form>

    <div class="demo">
      <p><strong>Single File Upload</strong></p>
      <input id="fileInput1" name="fileInput1" type="file" />
      <a href="javascript:$('#fileInput1').uploadifyUpload();">Upload Files</a>
    </div>


  </body>
</html>

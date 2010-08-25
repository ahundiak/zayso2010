<?php
  $extDir = '../tools/ext/';
  $title  = 'Arbiter Ref Availability Report';
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $extDir; ?>resources/css/ext-all.css" />
    <script type="text/javascript" src="<?php echo $extDir; ?>adapter/ext/ext-base.js"> </script>
    <script type="text/javascript" src="<?php echo $extDir; ?>ext-all-debug.js"> </script>

    <link rel="stylesheet" type="text/css" href="<?php echo $extDir; ?>ux/fileuploadfield/css/fileuploadfield.css"/>
    <script type="text/javascript" src="<?php echo $extDir; ?>ux/fileuploadfield/FileUploadField.js"></script>
    
    <!--
    <script type="text/javascript" src="VolGrid.js"></script>
    -->
    <script type="text/javascript" src="ref-avail.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo $extDir; ?>shared/examples.css" />
    <style type="text/css">
        .upload-icon {
            background: url('<?php echo $extDir; ?>shared/icons/fam/image_add.png') no-repeat 0 0 !important;
        }
        #fi-button-msg {
            border: 2px solid #ccc;
            padding: 5px 10px;
            background: #eee;
            margin: 5px;
            float: left;
        }
    </style>
</head>
<body>
    <h1>Upload Referee Availability Report</h1>
    <div id="fi-form"></div>
</body>
</html>

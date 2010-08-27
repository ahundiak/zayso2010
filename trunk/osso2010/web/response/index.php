<?php
error_reporting(E_ALL);

$ws = '/home/ahundiak/zayso2010/';

require_once $ws . 'Cerad/library/Cerad/Response.php';

$response = new Cerad_Response();

$html = <<<EOT
<html>
  <head>
    <title>Response Testing</title>
  </head>
  <body>
    <h3>More response testing</h3>
  </body>
</html>
EOT;

$response->setHeader('Cache-Control','must-revalidate, post-check=0,pre-check=0');
$response->setHeader('Pragma','public');

$response->setBody($html);

$response->setRedirect('redirect.php');
$response->sendResponse();

//  echo $html;
?>
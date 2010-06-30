<?php
require_once 'setup.php';

class Web
{
  static function run()
  {
    $fc = new FC_IndexFC();
    
    $response = $fc->execute();
    
    header('Content-Type: text/html');
    echo $response;
  }
}
Web::run();
?>
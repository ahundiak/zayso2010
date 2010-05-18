<?php
require_once 'setup.php';

class Web
{
  static function run()
  {
    $fc = new FC_JSLoadFC();
    
    $response = $fc->execute();
    
    header('Content-Type: text/javascript');
    echo $response;
  }
}
Web::run();
?>
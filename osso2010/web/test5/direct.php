<?php
require_once 'setup.php';

class Web
{
  // Pulss the data from post then unsets it
	static function get(&$post,$name)
	{
		if (!isset($post[$name])) return null;
		$value = $post[$name];
		unset($post[$name]);
		return $value;
	}
  static function run()
  {
    // Most requests are json
    if(isset($GLOBALS['HTTP_RAW_POST_DATA']))
  	{
      $request = json_decode($GLOBALS['HTTP_RAW_POST_DATA'],true);
  	}
  	else
  	{
  		// Form submits use POST, not positive why, maybe file uploads?
  		if (!isset($_POST['extAction'])) exit('Direct - No data');
  		
  		$post = $_POST;
  		
  		$request = array(
        'action' => self::get($post,'extAction'),
        'method' => self::get($post,'extMethod'),
        'type'   => self::get($post,'extType'),
        'tid'    => self::get($post,'extTID'),
  		  'upLoad' => self::get($post,'extUpload'),
  		);
      // Data is everything left
  		$request['data'] = array($post);
  		
  		// echo json_encode($request);
  		// return;
  	}
  	
    $fc = new FC_DirectFC();
    
    $response = $fc->execute($request);
    
    header('Content-Type: text/javascript');
    echo json_encode($response);
  }
}
Web::run();
?>
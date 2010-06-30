<?php
class FC_DirectFC
{
	function execute($requests)
	{		
    // Always need a context
		$params = include('config/' . MYAPP_CONFIG_FILE);
		$context = new Cerad_Context($params);
		
		// For storing responses
    $responses = array();
		
    // Make sure have at least one, not needed if add check later
    if (count($requests) < 1) return $responses;
    
		// Might have multiple requests, if so have index array instead of names parameters
		if (!isset($requests[0])) $requests = array($requests);
		
		foreach($requests as $data) 
		{
			// Add a final sanity check here
			
      // Build class and method name
      $actionClassName  = 'Direct_' . $data['action'] . 'Action';
      $actionMethodName = $data['method'];
    
      $action = new $actionClassName($context);
    
      // Execute the action
      $result = $action->$actionMethodName($data['data'][0]);

      $responses[] = array
      (
        'type'   => 'rpc',
        'tid'    => $data['tid'],
        'action' => $data['action'],
        'method' => $data['method'],
        'result' => $result
      );
		}
    //sleep(5);
    return $responses;
	}
}
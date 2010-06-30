<?php
class FC_AjaxFC
{
	protected $actions = array
	(
	  'league-combo' => 'League_LeagueCombo',
	
	  'referee-schedule' => 'Referee_RefSchedAction',
	);
	
	function execute()
	{
		// Always need a context?
    $params = include('config/' . MYAPP_CONFIG_FILE);
    $context = new Cerad_Context($params);
		
		$params = include('../config/config.php');
		
		$context = new Cerad_Context($params);
		
		$request = $context->request;

		// Process the action
		$actionName = $request->get('action');
		if (isset($this->actions[$actionName]))
		{
			$actionClassName = $this->actions[$actionName];
			$action = new $actionClassName($context);
			$results = $action->execute();
			echo json_encode($results);
			return;
		}
		echo " $actionName ";
	}
}
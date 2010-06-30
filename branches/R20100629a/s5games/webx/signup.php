<?php
require_once 'config.php';

require_once 'Controller.php';

class SignupController extends Controller
{
    function executeGet()
	{
		$relocate = "location: schedule.php";
		
		$data = new Cerad_Data();
		
		// Need to have game and position
		$gameId = (int)$this->getGet('game');
		$posId  = (int)$this->getGet('pos');
		if (!$gameId || !$posId) return header($relocate);
			
		$data->gameId = $gameId;
		$data->posId  = $posId;
		
		// Session data if we have it
		$data->refRegion    = $this->getSess('ref_region');
		$data->refFirstName = $this->getSess('ref_first_name');
		$data->refLastName  = $this->getSess('ref_last_name');
		$data->refAysoid    = $this->getSess('ref_aysoid');
		$data->errors       = $this->getSess('ref_errors');

		if ($data->errors) $_SESSION['ref_errors'] = NULL;
		
		// Referee positions
		$data->posPickList = array(
			1 => 'Center Referee',
			2 => 'Assistant Referee 1',
			3 => 'Asssitant Referee 2'
		);
		// Regions
		$regionRepo = new Zayso_Repo_Region();
		$data->regionPickList = $regionRepo->getRegionPickList();
		
		// Query
		require_once 'Query.php';
		$query = new Query($this->getDb());
		$events = $query->queryEventsForIds($gameId);
		
		if (count($events) != 1) return header($relocate);
			
		$data->game = array_shift($events);
		
		// Cerad_Debug::dump($data->game);
		
		// Preprocess the officials
		$persons = array(
			1 => array('pos' => 'Center Referee', 'name' => '.'),
			2 => array('pos' => 'Assistant 1',    'name' => '.'),
			3 => array('pos' => 'Asssitant 2',    'name' => '.'),
		);
		$personsx = $data->game->getPersons();
		foreach($personsx as $personx) 
		{
			$name   = $personx->getFullName();
			$region = $personx->getRegionNumber();
			if ($region < 1000) $region = 'R0' . $region;
			else                $region = 'R'  . $region;
			
			$name = $region . ' ' . $name;
			$posId = $personx->getPosId();
			$persons[$posId]['name'] = $name;
		}
		$data->persons = $persons;
		
		// From View
		ob_start();
		include 'signup.phtml';
		$content = ob_get_clean();

		echo $content;		
	}
	function executePost()
	{
		$refRegion    = (int)$this->getPost('referee_region');
		$refFirstName =      $this->getPost('referee_first_name');
		$refLastName  =      $this->getPost('referee_last_name');
		$refAysoid    = (int)$this->getPost('referee_aysoid');
		
		if (!$refAysoid) $refAysoid = NULL;
		
		$errors = NULL;
		if (!$refRegion)    $errors[] = '*** Please enter your region number';
		if (!$refFirstName) $errors[] = '*** Please enter your first name';
		if (!$refLastName)  $errors[] = '*** Please enter your last name';
		
		$eventId = (int)$this->getPost('game_id');
		$posId   = (int)$this->getPost('pos_id');
		
		$relocate = "location: schedule.php";
		if (!$eventId || !$posId) return header($relocate);

		$_SESSION['ref_data']       = TRUE;
		$_SESSION['ref_region']     = $refRegion;	
		$_SESSION['ref_first_name'] = $refFirstName;	
		$_SESSION['ref_last_name']  = $refLastName;	
		$_SESSION['ref_aysoid']     = $refAysoid;
		$_SESSION['ref_errors']     = $errors;
			
		$relocate = "location: signup.php?game={$eventId}&pos={$posId}";
		
		if ($errors) return header($relocate);
		
		// See if have an existing record
		$db = $this->getDb();
		$sql = "SELECT * FROM event_personx WHERE event_id = :event_id AND event_person_type_id = :pos_id;";
		$params = array(
			'event_id' => $eventId,
			'pos_id'   => $posId,
		);
		$row = $db->fetchRow($sql,$params);
		if (!$row) {
			 $row['event_person_id'] = NULL;
		}
		// Xfer data
		$row['aysoid'] = $refAysoid;
		$row['fname']  = $refFirstName;
		$row['lname']  = $refLastName;
		$row['region'] = $refRegion;
		$row['year']   = 2009;
		$row['status'] = 1;
		$row['event_id']  = $eventId;
		$row['person_id'] = 0; 
		$row['event_person_type_id'] = $posId;
		$row['notes'] = NULL; 

		if ($row['event_person_id']) $db->update('event_personx','event_person_id',$row);
		else                         $db->insert('event_personx','event_person_id',$row);
		
		return header($relocate);
	}
}
$controller = new SignupController();
$controller->execute();
?>
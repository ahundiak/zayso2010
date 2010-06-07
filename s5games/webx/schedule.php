<?php
require_once 'config.php';

require_once 'Controller.php';

class ScheduleController extends Controller
{
    function executeGet()
	{
		$userName = $this->getUserName();

		// Session data
		$data = new Cerad_Data();
		if (isset($_SESSION['sched_data'])) 
		{
			$data->sort     = $_SESSION['sched_sort'];		
			$data->date1    = $_SESSION['sched_date1'];
			$data->date2    = $_SESSION['sched_date2'];
			$data->age1     = $_SESSION['sched_age1' ];
			$data->age2     = $_SESSION['sched_age2' ];
			$data->showCoed = $_SESSION['sched_show_coed'];
			$data->showGirl = $_SESSION['sched_show_girl'];
			
		}
		else 
		{
			$data->sort     = 1;
			$data->date1    = '20090605';
			$data->date2    = '20090607';
			$data->age1     = 'U10';
			$data->age2     = 'U19';
			$data->showCoed = TRUE;
			$data->showGirl = FALSE;
		}
		$datePickList = array(
			'20090605' => 'Fri, June 5',
			'20090606' => 'Sat, June 6',
			'20090607' => 'Sun, June 7',
		);
		
		$agePickList = array(
			'U10' => 'U10',
			'U12' => 'U12',
			'U14' => 'U14',
			'U16' => 'U16',
			'U19' => 'U19',
		);
		
		$sortPickList = array(
			'1' => 'Date,Time,Field',
			'2' => 'Date,Field,Time',
		);
		$sort = 1;
		
		// The games
		require_once 'Query.php';
		$query = new Query($this->getDb());
		$eventIds = $query->queryDistinctEvents($data);
		$events   = $query->queryEventsForIds($eventIds);
		
		// From View
		ob_start();
		include 'schedule.phtml';
		$content = ob_get_clean();

		echo $content;		
	}
	function executePost()
	{
		$_SESSION['sched_data']  = TRUE;
		$_SESSION['sched_sort']  = $this->getPost('sched_sort');		
		$_SESSION['sched_date1'] = $this->getPost('sched_date1');
		$_SESSION['sched_date2'] = $this->getPost('sched_date2');
		$_SESSION['sched_age1' ] = $this->getPost('sched_age1');
		$_SESSION['sched_age2' ] = $this->getPost('sched_age2');
		$_SESSION['sched_show_coed'] = $this->getPost('sched_show_coed',FALSE);
		$_SESSION['sched_show_girl'] = $this->getPost('sched_show_girl',FALSE);
		
		header("location: schedule.php");
	}
}
class DisplayEvent
{
	protected $view;
	protected $event;
	
	function __construct($view)
	{
		$this->view = $view;
	}
	function setEvent($event)
	{
		$this->event = $event;
	}
	function getId()
	{
		return $this->event->getId();
	}
	function getField()
	{
		return $this->view->escape($this->event->getFieldDesc());
	}
	function getDate()
	{
		return $this->view->formatDate($this->event->getDate());
	}
	function getTime()
	{
		return $this->view->formatTime($this->event->getTime());
	}
	function getTeams()
	{
		$homeTeamDesc = $this->event->getHomeTeam()->getDesc();
		$awayTeamDesc = $this->event->getAwayTeam()->getDesc();

		return 
			$this->view->escape($homeTeamDesc) . '<br />' . 
			$this->view->escape($awayTeamDesc);
		
	}
	function getPersons()
	{
		$persons = array(
			1 => array('pos' => 'CR',  'name' => '.', 'status' => 0),
			2 => array('pos' => 'AR1', 'name' => '.', 'status' => 0),
			3 => array('pos' => 'AR2', 'name' => '.', 'status' => 0),			
		);
		$personsx = $this->event->getPersons();
		foreach($personsx as $personx) 
		{
			$name   = $personx->getFullName();
			$status = $personx->getStatus();
			$region = $personx->getRegionNumber();
			if ($region < 1000) $region = 'R0' . $region;
			else                $region = 'R'  . $region;
			
			$name = $region . ' ' . $name;
			$posId = $personx->getPosId();
			$persons[$posId]['name']   = $name;
			$persons[$posId]['status'] = $status;			
		}
		$html = "<table>\n";
		foreach($persons as $posId => $person) 
		{
			$pos  = $person['pos'];
			if (1) {
				$eventId = $this->event->getId();
				$url = "signup.php?game={$eventId}&pos={$posId}";
				$pos = "<a href=\"$url\">$pos</a>";
			}
			switch($person['status'])
			{
				case 1:  $span = "<span style=\"color: green;\">"; break;
				default: $span = "<span>";
			}
			$name = $span . $this->view->escape($person['name']) . "</span>";
			
			$html .= "<tr><td>{$pos}</td><td>{$name}</td></tr>\n";
		}
		$html .= "</table>\n";
		
		return $html;
	}
}
$controller = new ScheduleController();
$controller->execute();

?>
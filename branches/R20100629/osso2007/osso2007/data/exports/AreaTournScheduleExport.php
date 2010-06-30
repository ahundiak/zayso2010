<?php
class Event
{
	public $id,$num,$date,$time,$field,$div;
	
	public $teams = array();
	public $refs  = array();
	
	function __construct($id,$num,$date,$time,$field,$div)
	{
		$this->id    = $id;
		$this->num   = $num;
		$this->date  = $date;
		$this->time  = $time;
		$this->field = $field;
		$this->div   = $div;
	}
	function addTeam($team)
	{
		$this->teams[$team->type] = $team;
	}
	function addRef($ref)
	{
		if ($ref->id) 
		{
		  $this->refs[$ref->type] = $ref;
		}
	}
	function __get($name)
	{
		switch($name)
		{
			case 'datex':
				// 2009-11-07T00:00:00.000
				$date = $this->date;
				return substr($date,0,4) . '-' . substr($date,4,2) . '-' . substr($date,6,2) . 'T00:00:00.000';
				
			case 'timex':
				// 1899-12-31T09:45:00.000
				$time = $this->time;
				return '1899-12-31T' . substr($time,0,2) . ':' . substr($time,2,2) . ':00.000';
				
			case 'brac':
				$tmp = explode(' ',$this->teams[1]->desc);
				if ((strlen($tmp[0]) == 2) && is_numeric($tmp[0][1])) return $tmp[0][0];
				return '';
				
			case 'homeTeam': return $this->teams[1]->desc;
			case 'awayTeam': return $this->teams[2]->desc;
			
			case 'cr': 
				if (isset($this->refs[10])) return $this->refs[10]->desc;
				return '';
				
			case 'ar1': 
				if (isset($this->refs[11])) return $this->refs[11]->desc;
				return '';
				
			case 'ar2': 
				if (isset($this->refs[12])) return $this->refs[12]->desc;
				return '';
				
			case 'fourth': 
				if (isset($this->refs[13])) return $this->refs[13]->desc;
				return '';
				
			case 'observer': 
				if (isset($this->refs[14])) return $this->refs[14]->desc;
				return '';
				
			case 'standby': 
				if (isset($this->refs[16])) return $this->refs[16]->desc;
				return '';
		}
	}
}
class Team
{
	public $type,$desc;
	
	function __construct($type,$desc)
	{
		$this->type = $type;
		$this->desc = $desc;
	}
	function __get($name)
	{
		switch($name)
		{
			case 'descx': 
				$tmp = explode(' ',$this->desc);
				if (strlen($tmp[0]) != 2) return $this->desc;
				return $this->desc;
		}
	}
}
class Ref
{
	public $id,$type,$fname,$lname,$region;
	
	public $cr10,$cr12,$cr14,$cr16,$cr19;
	public $ar10,$ar12,$ar14,$ar16,$ar19;
	
	function __construct($id,$type,$fname,$lname,$region)
	{
		$this->id     = $id;
		$this->type   = $type;
		$this->fname  = $fname;
		$this->lname  = $lname;
		$this->region = $region;
	}
	function __get($name)
	{
		switch($name)
		{
			case 'desc': 
			    return "{$this->region} {$this->fname} {$this->lname}";
			//  return "{$this->fname} {$this->lname}";
				
			case 'age': 
				if ($this->dob > '19890731') return 'Youth';
				return '';
				
		}
	}
}
class AreaTournScheduleExport
{
	function __construct($context)
	{
		$this->context = $context;
	}
	protected function getEvents()
	{
    	$db = $this->context->db;

        $sql = <<<EOT
SELECT
  event.event_id   AS event_id,
  event.event_num  AS event_num,
  event.event_date AS event_date,
  event.event_time AS event_time,
  field.descx      AS field,
  
  event_team.event_team_type_id AS team_type,
  sch_team.desc_short           AS team_desc,
  
  division.desc_pick  AS divx,
  
  event_person.event_person_type_id AS ref_type,
  person.fname     AS ref_fname,
  person.lname     AS ref_lname,
  person.person_id AS ref_id,
  unit.keyx        AS ref_region
   
FROM event

LEFT JOIN field ON field.field_id = event.field_id

LEFT JOIN event_team ON event_team.event_id = event.event_id

LEFT JOIN sch_team ON sch_team.sch_team_id = event_team.team_id

LEFT JOIN division ON division.division_id = sch_team.division_id

LEFT JOIN event_person ON event_person.event_id = event.event_id

LEFT JOIN person ON person.person_id = event_person.person_id

LEFT JOIN unit ON unit.unit_id = person.unit_id

WHERE
-- event.event_id IN (7834) AND
  event.event_date >= '20091114' AND
  event.event_date <= '20091115' AND
  event.schedule_type_id IN (3)

ORDER BY event.event_date,event.event_time,field;
EOT;

        $rows = $db->fetchAll($sql);

        $events = array();
        foreach($rows AS $row)
        {
        	$eventId = $row['event_id'];
        	if (isset($events[$eventId])) $event = $events[$eventId];
        	else
        	{
        		$event = new Event($eventId,$row['event_num'],$row['event_date'],$row['event_time'],$row['field'],$row['divx']);
        		$events[$eventId] = $event;
        	}

        	$team = new Team($row['team_type'],$row['team_desc']);
        	$event->addTeam($team);
        	
        	$ref = new Ref($row['ref_id'],$row['ref_type'],$row['ref_fname'],$row['ref_lname'],$row['ref_region']);
        	$event->addRef($ref);

        }
		// Zend_Debug::dump($events);
		return $events;
	}
	function getReferees($events)
	{
		$repo = new Ref_RefAvailRepo($this->context);
		
		$refs = array();
		foreach($events AS $event)
		{
			foreach($event->refs as $ref)
			{
				// Build list
				if (!isset($refs[$ref->id])) 
				{
					$item = $repo->load(1,$ref->id);
					// Zend_Debug::dump($item); die();
					
					$ref->aysoid = $item->aysoid;
					$ref->dob    = $item->dob;
					$ref->badge  = $item->refBadgex;
					
					$ref->phoneHome = $item->phoneHome;
					$ref->phoneWork = $item->phoneWork;
					$ref->phoneCell = $item->phoneCell;
					$ref->emailHome = $item->emailHome;
					$ref->emailWork = $item->emailWork;
					
					if (!$ref->badge)
					{
						//Zend_Debug::dump($ref);
						//Zend_Debug::dump($item);
						//die();
					}
					$refs[$ref->id] = $ref;
				}
				$refx = $refs[$ref->id];
				
				// Adjust game count
				switch($event->div)
				{
					case 'U10C': 
					case 'U10G': 
						switch($ref->type)
						{
							case 10: $refx->cr10++; break;
							case 11: 
							case 12: $refx->ar10++; break;
						}
						break;
						
					case 'U12C': 
					case 'U12G': 
						switch($ref->type)
						{
							case 10: $refx->cr12++; break;
							case 11: 
							case 12: $refx->ar12++; break;
						}
						break;
						
					case 'U14C': 
					case 'U14G': 
						switch($ref->type)
						{
							case 10: $refx->cr14++; break;
							case 11: 
							case 12: $refx->ar14++; break;
						}
						break;
						
					case 'U16C': 
					case 'U16G': 
						switch($ref->type)
						{
							case 10: $refx->cr16++; break;
							case 11: 
							case 12: $refx->ar16++; break;
						}
						break;
						
					case 'U19C': 
					case 'U19G': 
						switch($ref->type)
						{
							case 10: $refx->cr19++; break;
							case 11: 
							case 12: $refx->ar19++; break;
						}
						break;
				}
				// Grab some eayso
			}
		}
		// Zend_Debug::dump($refs);
		return $refs;
	}
	function process($xmlFileName = NULL)
	{
		$events = $this->getEvents();
		
		if ($this->context->user->isAdmin) $referees = $this->getReferees($events);
		else                               $referees = array();
		
        // Render
        ob_start();
        include 'exports/AreaTournScheduleTpl.xml.php';
        $out = ob_get_clean();
        // echo $out;
        
        // Save
        if (!$xmlFileName) return $out;
        
        $file = fopen($xmlFileName,'wt');
        fwrite($file,$out);
        fclose($file);        
 		
	}
}
?>
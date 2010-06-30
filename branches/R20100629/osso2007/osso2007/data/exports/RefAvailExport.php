<?php
class RefAvailDisplayItem
{
	protected $item;
	protected $BR = '&#10;';
	protected $teams = array();
	
	public function __construct($context)
	{
		$this->context = $context;
	}
	public function setItem($item)
	{
		$this->item = $item;
	}
	function __get($name)
	{
		$item = $this->item;
		
		switch($name)
		{
			case 'notes' : return $item->notes; break;
			
			case 'phones': return $this->getPhones(); break;
			case 'emails': return $this->getEmails(); break;
			case 'teams' : return $this->getTeams();  break;
			
			case 'divCR': return $this->getDiv($item->divCR); break;
			case 'divAR': return $this->getDiv($item->divAR); break;
			
			case 'day1': return $this->getAvail($item->day1); break;
			case 'day2': return $this->getAvail($item->day2); break;
			case 'day3': return $this->getAvail($item->day3); break;
			case 'day4': return $this->getAvail($item->day4); break;
			case 'day5': return $this->getAvail($item->day5); break;
			case 'day6': return $this->getAvail($item->day6); break;
			
			case 'aysoid': return 'A' . $item->aysoid; break;
			
			case 'modified':
				$modified = $item->modified;
				
				if (substr($modified,0,4) == '2000') {
					Zend_Debug::dump($item);
					die();
				}
				//2009-10-26T00:00:00.000
				//20091024144403
				$value =  
				  substr($modified,0,4) . '-' . substr($modified, 4,2) . '-' . substr($modified, 6,2) . 'T' .
				  substr($modified,8,2) . ':' . substr($modified,10,2) . ':' . substr($modified,12,2) . '.000';
				
				return $value;
				
				
			case 'age':
				if ($item->dob > '19900801') $value = 'DOB' . substr($item->dob,0,4);
				else                         $value = 'Adult';
				break;
				
			case 'badge':
        		switch($item->certRefType)
        		{
        			case 210: $badge = 'U08'; break;
        			case 220: $badge = 'AR';  break;
        			case 230: $badge = 'REG'; break;
        			case 240: $badge = 'INT'; break;
        			case 250: $badge = 'ADV'; break;
        			case 280: $badge = 'NA2'; break;
        			case 290: $badge = 'NAT'; break;
        			default:  $badge = 'UNK ' . $item->certRefType;
        		}
        		$value = $badge . ' ' . $item->certRefDate;
				break;
				
        	case 'haven':
        	    switch($item->certSafeHavenType)
        	    {
        		    case 101: $haven = 'OK'; break;
        		    case 102: $haven = 'OK'; break;
        		    default:  $haven = 'UNK'; break;
        	    }
        	    $value = $haven;
 				break;
					
			default: $value = $item->$name;			
		}
		return $value;
	}
	protected function getTeam($teamId)
	{
		if ($teamId < 1) return NULL;
		
		if (isset($this->teams[$teamId])) return $this->teams[$teamId];
		
		$sql = <<<EOT
SELECT
  phy_team.division_seq_num AS seq_num,
  unit.keyx            AS region,
  division.desc_pick   AS divx,
  coach.fname          AS coach_fname,
  coach.lname          AS coach_lname
  
FROM phy_team
  
LEFT JOIN unit ON unit.unit_id = phy_team.unit_id

LEFT JOIN division ON division.division_id = phy_team.division_id

LEFT JOIN phy_team_person ON phy_team_person.phy_team_id = phy_team.phy_team_id AND phy_team_person.vol_type_id = 16

LEFT JOIN person AS coach ON coach.person_id = phy_team_person.person_id

WHERE phy_team.phy_team_id = :phy_team_id;

EOT;
		$db = $this->context->db;
        $params = array('phy_team_id' => $teamId);
        $row = $db->fetchOne($sql,$params);
        
        $num = $row['seq_num'];
        if ($num < 10) $num = '0' . $num;
        
        $team = $row['divx'] . ' ' . $row['coach_fname'] . ' ' . $row['coach_lname'];
        
        $this->teams[$teamId] = $team;
        
        return $team;
        
		// Zend_Debug::dump($row); die();
	}
	protected function getDiv($key)
	{
		switch($key)
		{
			case  0: $value = 'None'; break;
			case  7: $value = 'U10B'; break;
			case  8: $value = 'U10G'; break;
			case 10: $value = 'U12B'; break;
			case 11: $value = 'U12G'; break;
			case 13: $value = 'U14B'; break;
		    case 14: $value = 'U14G'; break;
			case 16: $value = 'U16B'; break;
			case 17: $value = 'U16G'; break;
			case 19: $value = 'U19B'; break;
		    case 20: $value = 'U19G'; break;
		    default: $value = 'UNK'; 
		}
		return $value;
	}
	protected function getTeams()
	{
		$teams = NULL;
		$item = $this->item;
		
		$team = $this->getTeam($item->team1);
		if ($team)
		{
			if ($teams) $teams .= $this->BR;
			$teams .= $team;
			
		}
		$team = $this->getTeam($item->team2);
		if ($team)
		{
			if ($teams) $teams .= $this->BR;
			$teams .= $team;
			
		}
		$team = $this->getTeam($item->team3);
		if ($team)
		{
			if ($teams) $teams .= $this->BR;
			$teams .= $team;
			
		}
		return $teams;
	}
	protected function getEmails()
	{
		$emails = NULL;
		$item = $this->item;
		
		if ($item->emailHome) {
			if ($emails) $emails .= $this->BR;
			$emails .= $item->emailHome;
		}
		if ($item->emailWork) {
			if ($emails) $emails .= $this->BR;
			$emails .= $item->emailWork;
		}
		return $emails;
	}
	protected function getPhones()
	{
		$phones = NULL;
		$item = $this->item;
		
		if ($item->phoneHome) {
			if ($phones) $phones .= $this->BR;
			$phones .= 'H: ' . $item->phoneHome;
		}
		if ($item->phoneWork) {
			if ($phones) $phones .= $this->BR;
			$phones .= 'W: ' . $item->phoneWork;
		}
		if ($item->phoneCell) {
			if ($phones) $phones .= $this->BR;
			$phones .= 'C: ' . $item->phoneCell;
		}
		
		return $phones;
	}
	protected function getAvail($avail)
	{
		switch($avail)
		{
			case 0:  $value = 'Dont know'; break; // 'Do not know yet'
			case 1:  $value = 'Not Avail'; break; // Not available
			case 2:  $value = 'All Day';   break;
			case 3:  $value = 'Morning';   break;
			case 4:  $value = 'Afternoon'; break;
			case 5:  $value = 'If needed'; break;
			case 6:  $value = 'Remarks';   break;
			default: $value = 'UNK';
		}
		return $value;
	}
}
class RefAvailExport extends ExportBase
{
	protected function getReferees()
	{
		$sql = <<<EOT
SELECT 
  ref_avail.person_id AS person_id,
  person.fname AS person_fname,
  person.lname AS person_lname,
  person.aysoid AS person_aysoid,
  unit.keyx    AS person_region
  
FROM ref_avail

LEFT JOIN person ON person.person_id = ref_avail.person_id

LEFT JOIN unit ON unit.unit_id = person.unit_id

ORDER BY unit.keyx,person.lname,person.fname;

EOT;

		$db = $this->context->db;
        //$params = array('person_id' => $personId);
        $rows = $db->fetchAll($sql);
		
		$repo = new Ref_RefAvailRepo($this->context);
        
        $items = array();
        foreach($rows as $row)
        {
        	$item = $repo->load(1,$row['person_id']);
        	       	
        	$items[] = $item;
        }
        Zend_Debug::dump($items[0]);   

        return $items;
	}
	public function process($xmlFileName)
    {
    	$this->referees = $this->getReferees();
    	
    	// Render
        ob_start();
        include 'exports/RefAvailTpl.xml.php';
        $out = ob_get_clean();
       
        // Save
        if (!$xmlFileName) return $out;
        
        $file = fopen($xmlFileName,'wt');
        fwrite($file,$out);
        fclose($file);       
    }
}
?>
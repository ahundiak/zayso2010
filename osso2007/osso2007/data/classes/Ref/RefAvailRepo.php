<?php
class Ref_RefAvailItem
{
	protected $data = array
	(
		'id'        => 0,
	    'personId'  => 0,
		'groupId'   => 0,
		'aysoid'    => NULL,
		'fname'     => NULL,
		'nname'     => NULL,
		'lname'     => NULL,
		'region'    => NULL,
		'regionId'  => 0,
		'season'    => NULL,
		'dob'       => NULL,
		'gender'    => NULL,
		'phoneHome' => NULL,
		'phoneWork' => NULL,
		'phoneCell' => NULL,
		'emailHome' => NULL,
		'emailWork' => NULL,
	
		'certRefType'       => NULL,
	    'certRefDate'       => NULL,
	    'certRefDesc'       => NULL,
	
	    'certSafeHavenType' => NULL,
	    'certSafeHavenDate' => NULL,
	    'certSafeHavenDesc' => NULL,
	
		'divCR' => 0,
		'divAR' => 0,
	
		'day1'  => 0,
		'day2'  => 0,
		'day3'  => 0,
		'day4'  => 0,
		'day5'  => 0,
		'day6'  => 0,
	
		'team1' => 0,
		'team2' => 0,
		'team3' => 0,
	
		'modified' => '20000000123456',
		'notes'    => NULL,
	
		'teams' => array(),
	
	);
    protected function escape($str)
    {
    	return htmlspecialchars($str);
    }
	function __get($name)
	{
		if (array_key_exists($name,$this->data)) return $this->data[$name];
		
		switch($name)
		{
			case 'name':
				$name = $this->fname . ' ' . $this->lname;
				$name = substr($name,0,20);
				return $this->escape($name);
				
			case 'safeHaven':
				switch($this->certSafeHavenType)
				{
					case 101: return 'Referee';
					case 102: return 'Coach';
					default:  return NULL;
				}
			case 'refBadge':
				
				switch($this->certRefType)
				{
					case 210: $badge = 'U8 Official';  break;
					case 220: $badge = 'Assistant';    break;
					case 230: $badge = 'Regional';     break;
					case 240: $badge = 'Intermediate'; break;
					case 250: $badge = 'Advanced';     break;
					case 268: $badge = 'National 2';   break;
					case 290: $badge = 'National';     break;
					
					default:  return NULL;
				}
				return $badge . ' ' . $this->certRefDate;
				
			case 'refBadgex':
				
				switch($this->certRefType)
				{
					case 210: $badge = 'U08';  break;
					case 220: $badge = 'AR ';  break;
					case 230: $badge = 'REG';  break;
					case 240: $badge = 'INT';  break;
					case 250: $badge = 'ADV';  break;
					case 280: $badge = 'NA2';  break;
					case 290: $badge = 'NAT';  break;
					
					default:  return NULL;
				}
				return $badge . ' ' . $this->certRefDate;
		}
		die("Could not get $name");
	}
	public function __set($name,$value)
	{
		if (array_key_exists($name,$this->data)) {
			$this->data[$name] = $value;
			return;
		}
		die("Bad attribute name for set: {$name}\n");
	}
}
class Ref_RefAvailRepo
{
	protected $context;
	
	protected $map = array
	(
		'ref_avail_id' => 'id',
	    'person_id'    => 'personId',
		'group_id'     => 'groupId',
	
		'phone_home' => 'phoneHome',
		'phone_work' => 'phoneWork',
		'phone_cell' => 'phoneCell',
		'email_home' => 'emailHome',
		'email_work' => 'emailWork',
	
		'div_cr_id'  => 'divCR',
		'div_ar_id'  => 'divAR',
	
		'avail_day1' => 'day1',
		'avail_day2' => 'day2',
		'avail_day3' => 'day3',
		'avail_day4' => 'day4',
		'avail_day5' => 'day5',
		'avail_day6' => 'day6',
	
		'team_id1' => 'team1',
		'team_id2' => 'team2',
		'team_id3' => 'team3',	
	
		'modified' => 'modified',
		'notes'    => 'notes',
	);
	public function __construct($context)
	{
		$this->context = $context;
	}
	public function save($item)
	{
		$item->modified = date('YmdHis',time());
		
		$data = array();
		foreach($this->map as $colName => $itemName)
		{
			$data[$colName] = $item->$itemName;
		}
		if ($item->id) 
		{
			// These never change and cause unique key violation
			//unset($data['group_id']);
			//unset($data['person_id']);
			$this->context->db->update('ref_avail','ref_avail_id',$data);
		}
		else
		{
			$this->context->db->insert('ref_avail','ref_avail_id',$data);
			$item->id = $this->context->db->lastInsertId();
		}
	}
	public function load($groupId,$personId)
	{
		
		// Build one from stratch
        $sql = <<<EOT
SELECT
    person.person_id AS person_id,
    person.fname     AS person_fname,
    person.lname     AS person_lname,
    person.aysoid    AS person_aysoid,
    unit.keyx        AS person_region,
    unit.unit_id     AS person_region_id,
        
    CONCAT('(',phone_home.area_code,') ',phone_home.num) AS person_phone_home,
    CONCAT('(',phone_work.area_code,') ',phone_work.num) AS person_phone_work,
    CONCAT('(',phone_cell.area_code,') ',phone_cell.num) AS person_phone_cell,
            
    email_home.address as person_email_home,
    email_work.address as person_email_work
       
FROM person

LEFT JOIN unit ON unit.unit_id = person.unit_id

LEFT JOIN phone AS phone_home 
  ON  phone_home.person_id = person.person_id 
  AND phone_home.phone_type_id = 1
  
LEFT JOIN phone AS phone_work 
  ON  phone_work.person_id = person.person_id 
  AND phone_work.phone_type_id = 2
  
LEFT JOIN phone AS phone_cell
  ON  phone_cell.person_id = person.person_id 
  AND phone_cell.phone_type_id = 3
  
LEFT JOIN email AS email_home
  ON  email_home.person_id = person.person_id 
  AND email_home.email_type_id = 1

LEFT JOIN email AS email_work
  ON  email_work.person_id = person.person_id 
  AND email_work.email_type_id = 2
  
WHERE person.person_id = :person_id
;
EOT;

        $db = $this->context->db;
        $params = array('person_id' => $personId);
        $row = $db->fetchOne($sql,$params);
        
        $person = new Ref_RefAvailItem();
        $person->personId = $personId;
        $person->groupId  = $groupId;
        
        if (!$row) return $person;
        
        $person->id        = 0; // $row['person_id'];
        $person->fname     = $row['person_fname'];
        $person->lname     = $row['person_lname'];
        $person->aysoid    = $row['person_aysoid'];
        $person->region    = $row['person_region'];
        $person->regionId  = $row['person_region_id'];
                
        $person->phoneHome = $row['person_phone_home'];
        $person->phoneWork = $row['person_phone_work'];
        $person->phoneCell = $row['person_phone_cell'];
        $person->emailHome = $row['person_email_home'];
        $person->emailWork = $row['person_email_work'];
         
        // Now stuff from eayso
        if (!$person->aysoid) return $person;
        
        $dbEayso = $this->context->dbEayso;
        
        $sql = <<<EOT
SELECT 
  season,dob,gender,
  cert_safe_haven.cert_type AS cert_safe_haven_type,
  cert_safe_haven.cert_date AS cert_safe_haven_date,
  cert_ref.cert_type        AS cert_ref_type,
  cert_ref.cert_date        AS cert_ref_date
  
FROM eayso_vol

LEFT JOIN eayso_vol_cert AS cert_safe_haven
  ON  cert_safe_haven.aysoid = eayso_vol.aysoid
  AND cert_safe_haven.cert_cat = 100
  
LEFT JOIN eayso_vol_cert AS cert_ref
  ON  cert_ref.aysoid = eayso_vol.aysoid
  AND cert_ref.cert_cat = 200
    
WHERE eayso_vol.aysoid = :aysoid;
EOT;
		$params = array('aysoid' => $person->aysoid);
		
        $row = $dbEayso->fetchOne($sql,$params);
		if ($row) {
		
		  $person->season = $row['season'];
		  $person->dob    = $row['dob'];
	      $person->gender = $row['gender'];
	    
	      $person->certRefType       = $row['cert_ref_type'];
	      $person->certRefDate       = $row['cert_ref_date'];
	      $person->certSafeHavenType = $row['cert_safe_haven_type'];
	      $person->certSafeHavenDate = $row['cert_safe_haven_date'];
		}
		
	    // Now see if avail record is present
		$sql = <<<EOT

SELECT * FROM ref_avail 
WHERE group_id = :group_id AND person_id = :person_id;
EOT;

		$params = array(
			'group_id'  => $groupId,
			'person_id' => $personId,
		);
		
        $row = $db->fetchOne($sql,$params);
		if (!$row) return $person;
		
		foreach($this->map as $colName => $itemName)
		{
			$person->$itemName = $row[$colName];
		}
        return $person;
	}
}
?>
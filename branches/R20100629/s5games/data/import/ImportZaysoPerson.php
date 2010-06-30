<?php
class ImportZaysoPerson extends Cerad_ExcelReader
{
    protected $map = array(
        //'Region'    => 'region',
        'aysoid'      => 'aysoid',
        //'LastName'  => 'lname',
        //'FirstName' => 'fname',
        //'NickName'  => 'nname',
    	//'DOB'       => 'dob',
        //'Gender'    => 'gender',
        //'HomePhone' => 'phone_home',
        //'WorkPhone' => 'phone_work',
        //'WPhoneExt' => 'phone_work_ext',
        //'CellPhone' => 'phone_cell',
        //'Email'     => 'email',
        //'Season'    => 'season',
        //'Status'    => 'status',
    );
    protected $eaysoRepo  = NULL;
    protected $personRepo = NULL;
    
    public function init()
    {
    	$this->eaysoRepo  = new Zayso_Repo_Eayso();
    	$this->personRepo = new Zayso_Repo_Person($this);
    }
    public function processRowData($data)
    {
        // Validation
        $valid = TRUE;
        if (!$data['aysoid']) { 
        	return; // Some older records without a region
        }
        $aysoid = $data['aysoid'];
        
        // print_r($data);
        
        // Get the eayso record
        $vol = $this->eaysoRepo->findForAysoid($aysoid);
        if (!$vol->getId()) {
        	echo "*** No eayso record for {$aysoid}\n";
        	return;
        }
		
		// See if already in Zayso
        $person = $this->personRepo->findForAysoid($aysoid);
        if ($person->getId()) {
        	echo "### Have zayso record for {$aysoid}\n";
        	return;
        }
        
        echo "{$aysoid} {$vol->getFullName()}\n";
        
        /* ==========================================
         * Not sure the best way to proceed here
         * Straight arrays are fast and should be okay
         * But no very oop
         */
        $db = $this->getDb();
        
        $data = array();
        $data['person_id'] = 0;
        $data['fname']  = $vol->getFirstName();
        $data['lname']  = $vol->getLastName();
        $data['nname']  = $vol->getNickName();
        $data['aysoid'] = $aysoid;
        $data['status'] = 1;
        
        // Need region id
        
        $db->insert('person','person_id',$data);
        $id = $db->lastInsertId();
        
		$email = trim($vol->getEmail());
		if ($email) {
			$data = array();
			$data['email_id'] = 0;
			$data['address'] = $email;
			$data['person_id'] = $id;
			$data['email_type_id'] = 1;
			$db->insert('email','email_id',$data);
		}
		
		// Phones need to explode and what not
		$data = array();
		$data['phone_id'] = 0;
		$data['person_id'] = $phone;
		
        die("Inserted $id\n");
        
		return;
				
        // Clean up dob
        $dob = $data['dob'];
        $data['dob'] = substr($dob,0,4) . substr($dob,5,2) . substr($dob,8,2);
        
        // Clean up phone work extension
        if ($data['phone_work_ext']) {
            $data['phone_work'] = $data['phone_work'] . 'x' . $data['phone_work_ext'];
        }
        unset($data['phone_work_ext']);
        // print_r($data); die();
        
        // Already exist?
        $db = $this->getDb();
        $datax = $db->find('eayso_vol','aysoid',$data['aysoid']);
        if (!$datax) {
            
            // Create new one
            $db->insert('eayso_vol','eayso_vol_id',$data);
            return;
        }
        // Use the latest season data
        $season  = $data ['season'];
        $seasonx = $datax['season'];
        if (substr($season, 0,2) != 'FS') $season  = NULL;
        if (substr($seasonx,0,2) != 'FS') $seasonx = NULL;
        if (($season) && ($season >= $seasonx)) {
            $data['eayso_vol_id'] = $datax['eayso_vol_id'];
            $db->update('eayso_vol','eayso_vol_id',$data);
        }
        
        // if ($this->count > 10) die();    
    }
}
?>

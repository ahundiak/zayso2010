<?php
class ImportEaysoVolReg extends Cerad_ExcelReader
{
    protected $map = array(
        'Region'    => 'region',
        'AYSOID'    => 'aysoid',
        'LastName'  => 'lname',
        'FirstName' => 'fname',
        'NickName'  => 'nname',
    	'DOB'       => 'dob',
        'Gender'    => 'gender',
        'HomePhone' => 'phone_home',
        'WorkPhone' => 'phone_work',
        'WPhoneExt' => 'phone_work_ext',
        'CellPhone' => 'phone_cell',
        'Email'     => 'email',
        'Season'    => 'season',
        'Status'    => 'status',
    );  
    public function processRowData($data)
    {
        // Validation
        $valid = TRUE;
        if (!$data['region']) { 
        	return; // Some older records without a region
        }
        if (!$data['aysoid']) { $valid = FALSE; echo "Missing aysoid\n";  }
        if (!$data['lname'])  { $valid = FALSE; echo "Missing last name\n"; }
        if (!$valid) {
            print_r($data);
            return;
        }

        // Clean up dob
        $dob = $data['dob'];
        $data['dob'] = substr($dob,0,4) . substr($dob,5,2) . substr($dob,8,2);
        
        // Clean up phone work extension
        $phoneWorkExt = trim($data['phone_work_ext']);
        if ($phoneWorkExt) {
            $data['phone_work'] = $data['phone_work'] . 'x' . $phoneWorkExt;
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

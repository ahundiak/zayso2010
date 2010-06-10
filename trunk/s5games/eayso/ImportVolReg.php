<?php
class Eayso_ImportVolReg extends Cerad_ExcelReader
{
    protected $map = array(
        'Region'    => 'region',

        'Registered Date' => 'regDate',

        'AYSOID'    => 'aysoid',
        'LastName'  => 'lname',
        'FirstName' => 'fname',
        'NickName'  => 'nname',
        'MI'        => 'mname',
    	'DOB'       => 'dob',
        'Gender'    => 'gender',
        'HomePhone' => 'phoneHome',
        'WorkPhone' => 'phoneWork',
        'WorkPhoneExt'   => 'phoneWorkExt',
        'CellPhone'      => 'phoneCell',
        'Email'          => 'email',
        'Membershipyear' => 'season',
//      'Status'         => 'status',
    );
    public function getMappedData($item)
    {
        $data = array
        (
            'aysoid' => $item->aysoid,

            'fname'  => $item->fname,
            'lname'  => $item->lname,
            'nname'  => $item->nname,
            'mname'  => $item->mname,
            'email'  => $item->email,

            'gender' => $item->gender,

            'phone_home' => $item->phoneHome,
            'phone_work' => $item->phoneHome,
            'phone_cell' => $item->phoneHome,

            'season' => $item->season,
            'status' => 1,  // No more status in eayso2

            'region' => (int)$item->region,

        );
        // Clean up dob
        $dob = $item->dob;
        if (!$dob) $dob = ' UNKNOWN';
        else {
            $dob = $this->getDateFromExcelFormat($dob);
        }
        $data['dob'] = $dob;

        // Cleanup reg date
        $data['reg_date'] = $this->getDateFromExcelFormat($item->regDate);

        // Check Gender
        if (($data['gender'] != 'M') && ($data['gender'] != 'F'))
        {
            $data['gender'] = '?';
        }
        // Work phone extension
        if ($item->phoneWorkExt)
        {
            $data['phone_work'] = $data['phone_work'] . 'x' . $item->phoneWorkExt;
        }
        return $data;
    }
    protected $aysoids = array();
    
    public function processRowData($item)
    {
        // Validation
        $valid = TRUE;
        if (!$item->region) {
        	return; // Some older records without a region
        }
        if (!$item->aysoid) { $valid = FALSE; echo "Missing aysoid\n";  }
        if (!$item->lname)  { $valid = FALSE; echo "Missing last name\n"; }
        if (!$valid) {
            Cerad_Debug::dump($item);
            return;
        }
        // Just for grins, check for dup aysoids
        //if (isset($this->aysoids[$item->aysoid])) echo "Dup {$item->aysoid} \n";
        $this->aysoids[$item->aysoid] = $item->aysoid;
        
        // Already exist?
        $db = $this->context->dbEayso;
        $row = $db->find('eayso_vol','aysoid',$item->aysoid);
        if (!$row)
        {
            $data = $this->getMappedData($item);
            Cerad_Debug::dump($data); die();

            // Create new one
            $db->insert('eayso_vol','eayso_vol_id',$data);
            return;
        }
        // Check for newer season
        if ($item->season < $row['season']) return;

        if ($item->season > $row['season'])
        {
            $data = $this->getMappedData($item);
            $data['eayso_vol_id'] = $row['eayso_vol_id'];

            $db->update('eayso_vol','eayso_vol_id',$data);
            return;

        }
        // Not sure if should always update or not, wait and see
    }
}
?>

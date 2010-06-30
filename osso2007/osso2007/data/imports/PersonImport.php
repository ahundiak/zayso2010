<?php
class PersonImport extends ExcelReader
{
    protected $map = array(
        'Region'    => 'region',
        'AYSOID'    => 'aysoid',
        'LastName'  => 'lname',
        'FirstName' => 'fname',
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
    protected $workSheetNames = array('People');
     
    public function processRowData($data)
    {
        // Validation
        $valid = TRUE;
        if (!$data->aysoid) { $valid = FALSE; echo "Missing aysoid\n";  }
        if (!$data->region) { $valid = FALSE; echo "Missing region\n";  }
        if (!$data->lname)  { $valid = FALSE; echo "Missing last name\n"; }
        if (!$valid) {
            print_r($data);
            return;
        }

        // Clean up dob
        $dob = $data->dob;
        $data->dob = substr($dob,0,4) . substr($dob,5,2) . substr($dob,8,2);
        
        // Clean up phone work extension
        if ($data->phone_work_ext) {
            $data->phone_work = $data->phone_work . 'x' . $data->phone_work_ext;
        }
        unset($data->phone_work_ext);
        // print_r($data);
        
        // Validate
        $models = $this->context->models;
        $personModel = $models->PersonModel;
            
        // Check for existing aysoid
        $search = new SearchData();
        $search->aysoid = $data->aysoid;
        $person = $models->PersonModel->searchOne($search);
        if ($person) {
        	// For now just ignore
        	return;
        }
        // Verify the region
        $region = $models->UnitModel->searchByNumber($data->region);
        if (!$region) {
        	echo "Missing Region\n";
        	print_r($data);
        	return;
        }
        // Insert person
        $person = $models->PersonModel->find(0);
        $person->fname  = $data->fname;
        $person->lname  = $data->lname;
        $person->aysoid = $data->aysoid;
        $person->unitId = $region->id;
        $person->status = 1;
        $personId = $models->PersonModel->save($person);
        
        // Insert Email
        if ($data->email) {
        	$email = $models->EmailModel->find(0);
        	$email->personId = $personId;
        	$email->address  = $data->email;
        	$email->emailTypeId = $models->EmailTypeModel->TYPE_HOME;
        	$models->EmailModel->save($email);
        }
        // And the phones
        $this->insertPersonPhone($personId,$data->phone_home,$models->PhoneTypeModel->TYPE_HOME);
        $this->insertPersonPhone($personId,$data->phone_work,$models->PhoneTypeModel->TYPE_WORK);
        $this->insertPersonPhone($personId,$data->phone_cell,$models->PhoneTypeModel->TYPE_CELL);
        
        echo "{$personId} {$person->lname} {$person->fname}\n";
    }
    function insertPersonPhone($personId,$phoneNumber,$type)
    {
    	if (!$phoneNumber) return;
    	$models = $this->context->models;
    	$phone = $models->PhoneModel->find(0);
    	$phone->personId = $personId;
    	$phone->phoneTypeId = $type;
    	$phone->areaCode = substr($phoneNumber,0,3);
    	$phone->num = substr($phoneNumber,3,3) . '-' . substr($phoneNumber,6,4);
    	$phone->ext = substr($phoneNumber,10);
    	$models->PhoneModel->save($phone);
    }
}
?>
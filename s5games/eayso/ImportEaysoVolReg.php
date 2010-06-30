<?php
class ImportEaysoVolReg extends Cerad_Reader_CSV
{
  public $countInsert = 0;
  public $countUpdate = 0;

  protected $map = array(
    'Region'         => 'region',
    'AYSOID'         => 'aysoid',
    'LastName'       => 'lname',
    'FirstName'      => 'fname',
    'NickName'       => 'nname',
    'DOB'            => 'dob',
    'Gender'         => 'gender',
    'HomePhone'      => 'phone_home',
    'WorkPhone'      => 'phone_work',
    'WorkPhoneExt'   => 'phone_work_ext',
    'CellPhone'      => 'phone_cell',
    'Email'          => 'email',
    'Membershipyear' => 'mem_year',
  );  
  protected function processRowData($data)
  {
    // Validation
    $valid = TRUE;
    if (!$data['region']) { $valid = FALSE; echo "Missing region\n";  }
    if (!$data['aysoid']) { $valid = FALSE; echo "Missing aysoid\n";  }
    if (!$data['lname'])  { $valid = FALSE; echo "Missing last name\n"; }
    if (!$valid)
    {
      Cerad_Debug::dump($data);
      return;
    }

    // Clean up dob
    $dobs = explode('/',$data['dob']);
    if (count($dobs) == 3)
    {
      if (strlen($dobs[0]) == 1) $month = '0' . $dobs[0];
      else                       $month =       $dobs[0];
      if (strlen($dobs[1]) == 1) $day   = '0' . $dobs[1];
      else                       $day   =       $dobs[1];

      $data['dob'] = $dobs[2] . $month . $day;
    }
        
    // Clean up phone work extension
    $phoneWorkExt = trim($data['phone_work_ext']);
    if ($phoneWorkExt)
    {
      $data['phone_work'] = $data['phone_work'] . 'x' . $phoneWorkExt;
    }
    unset($data['phone_work_ext']);
    
    // Cerad_Debug::dump($data); die();
        
    // Already exist?
    $db = $this->context->getDb();
    $datax = $db->find('eayso_vols','aysoid',$data['aysoid']);
    if (!$datax)
    {
      // Create new one
      $db->insert('eayso_vols','eayso_vol_id',$data);
      $this->countInsert++;
      return;
    }
    // Use the latest membership year data
    $my  = $data ['mem_year'];
    $myx = $datax['mem_year'];
    if ($my >= $myx)
    {
      $data['eayso_vol_id'] = $datax['eayso_vol_id'];
      $db->update('eayso_vols','eayso_vol_id',$data);
      $this->countUpdate++;
    }
  }
}
?>

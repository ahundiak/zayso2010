<?php
class Eayso_Import_Vol extends Cerad_Reader_CSV
{
  public $countInsert = 0;
  public $countUpdate = 0;
  public $countTotal  = 0;
  
  public $countInsertVolRegion;

  protected $map = array(
    'Region'         => 'region',
    'AYSOID'         => 'aysoid',
    'LastName'       => 'lname',
    'FirstName'      => 'fname',
    'NickName'       => 'nname',
    'MI'             => 'mname',
    'suffix'         => 'suffix',
    'DOB'            => 'dob',
    'Gender'         => 'gender',
    'HomePhone'      => 'phone_home',
    'WorkPhone'      => 'phone_work',
    'WorkPhoneExt'   => 'phone_work_ext',
    'CellPhone'      => 'phone_cell',
    'Email'          => 'email',
    'Membershipyear' => 'reg_year',
    'source'         => 'source',
  );
  protected $mapx = array(
    'source' => array('required' => false, 'default' => 'eayso_vol'),
  );
  
  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbEayso;

  }
  public function getResultMessage()
  {
    $msg = sprintf("Eayso Vol Import, Total: %u, Inserted: %u, Updated: %u",
      $this->countTotal,$this->countInsert,$this->countUpdate);
    return $msg;
  }
  protected function processRowData($data)
  {
    $this->countTotal++;
    
    // Validation
    $valid = TRUE;
    if (!$data['region']) { $valid = FALSE; $this->errors[] = "Missing region\n";  }
    if (!$data['aysoid']) { $valid = FALSE; $this->errors[] = "Missing aysoid\n";  }
    if (!$data['lname'])  { $valid = FALSE; $this->errors[] = "Missing last name\n"; }
    if (!$valid)
    {
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

    // Volunteer region
    $this->processVolRegion($data);
    
    // Already exist?
    $db = $this->context->dbEayso;
    $datax = $db->find('eayso_vol','aysoid',$data['aysoid']);
    if (!$datax)
    {
      // Create new one
      $data['ts_created'] = $this->ts;
      $data['ts_updated'] = $this->ts;

      $db->insert('eayso_vol','id',$data);
      $this->countInsert++;
      return;
    }
    // Use the latest membership year data
    $my  = $data ['reg_year'];
    $myx = $datax['reg_year'];
    if ($my >= $myx)
    {
      $changes = array();
      foreach(array_keys($data) as $key)
      {
        if ($data[$key] != $datax[$key]) $changes[$key] = $data[$key];
      }
      if (count($changes))
      {
        $changes['id']         = $datax['id'];
        $changes['ts_created'] = $datax['ts_created'];
        $changes['ts_updated'] = $this->ts;
        $db->update('eayso_vol','id',$changes);
        $this->countUpdate++;
      }
    }
  }
  protected function processVolRegion($data)
  {
    $db = $this->db;
    
    // Look for existing record
    $search['aysoid'] = $data['aysoid'];
    $search['region'] = $data['region'];
    $sql = 'SELECT * FROM eayso_vol_region WHERE aysoid = :aysoid AND region = :region';
    $row = $db->fetchRow($sql,$search);
    if ($row === FALSE)
    {
      $datax = array
      (
        'aysoid' => $data['aysoid'],
        'fname'  => $data['fname'],
        'lname'  => $data['lname'],
        'region' => (int)$data['region'],
        'source'     => $data['source'],
        'ts_created' => $this->ts,
        'ts_updated' => $this->ts,
      );
      $db->insert('eayso_vol_region','id',$datax);
      $this->countInsertVolRegion++;
      return;
    }
    return;
  }
}
?>

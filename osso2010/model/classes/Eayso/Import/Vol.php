<?php
class Eayso_Import_Vol extends Cerad_Import
{
  protected $readerClassName = 'Eayso_Import_VolReader';
  protected $regions = array();

  protected $countInsertVolRegion = 0;

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso;
    $this->ts = $this->context->getTimeStamp();
  }
  public function getResultMessage()
  {
    $file = basename($this->innFileName);

    $msg = sprintf("Eayso Vol Import %s, Total: %u, Inserted: %u, Updated: %u, Org Inserted: %u",
      $file,
      $this->countTotal,$this->countInsert,$this->countUpdate,$this->countInsertVolRegion);
    return $msg;
  }
  public function processRowData($data)
  {   
    // Validation
    $valid = TRUE;
    if (!$data['region']) { $valid = FALSE; }
    if (!$data['person_reg_num']) { $valid = FALSE; }
    if (!$data['lname'])  { $valid = FALSE; }
    if (!$valid) { return; }

    $this->countTotal++;

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

    // Clean up registration year
    $data['person_reg_year'] = $this->processYear($data['person_reg_year']);

    // Volunteer region
    $region = $data['region'];
    unset($data['region']);

    // Already exist?
    $db = $this->db;
    $search['type']   = 2;
    $search['aysoid'] = $data['person_reg_num'];
    $sql = 'SELECT * FROM person_reg WHERE person_reg_type_id = :type AND person_reg_num = :aysoid;';
    $datax = $db->fetchRow($sql,$search);
    
    if ($datax === FALSE)
    {
      // Create new one
      $data['person_id']          = 0;
      $data['person_reg_type_id'] = 2;
      $data['ts_created'] = $this->ts;
      $data['ts_updated'] = $this->ts;
      $data['email2']     = '';

      $db->insert('person_reg','id',$data);
      $this->countInsert++;

      // Add any regions
      $id = $db->lastInsertId();
      $this->processVolRegion($id,$region);
      return;
    }
    $this->processVolRegion($datax['id'],$region);

    // Use the latest membership year data
    $my  = $data ['person_reg_year'];
    $myx = $datax['person_reg_year'];
    
    if ($my >= $myx)
    {
      unset($datax['ts_created']);
      unset($datax['ts_modified']);

      $changes = array();
      foreach(array_keys($data) as $key)
      {
        if ($data[$key] != $datax[$key]) $changes[$key] = $data[$key];
      }
      if (count($changes))
      {
        $changes['id']         = $datax['id'];
        $changes['ts_updated'] = $this->ts;

        /*
        Cerad_Debug::dump($data);
        Cerad_Debug::dump($datax);
        Cerad_Debug::dump($changes);
        die(); */
        
        $db->update('person_reg','id',$changes);
        $this->countUpdate++;

      }
    }
  }
  protected function processVolRegion($personRegId,$region)
  {
    $db = $this->db;

    // Need to find the org_id for the region
    if (!isset($this->regions[$region]))
    {
      $keyx = sprintf('R%04u',$region);
      $org = $db->find('org','keyx',$keyx);
      if (!$org)
      {
        echo("Could not find region: $region\n"); // Some regions are revoked
        $this->regions[$region] = 0;
        return;
      }
      $this->regions[$region] = $org['id'];
    }
    $orgId = $this->regions[$region];
    if (!$orgId) return;

    // Look for existing record
    $search['org_id']        = $orgId;
    $search['person_reg_id'] = $personRegId;
    $sql = 'SELECT * FROM person_reg_org WHERE org_id = :org_id AND person_reg_id = :person_reg_id';
    $row = $db->fetchRow($sql,$search);
    if ($row === FALSE)
    {
      $db->insert('person_reg_org','id',$search);
      $this->countInsertVolRegion++;
      return;
    }
    return;
  }
  protected function processYear($year)
  {
    // Clean up registration year
    $yearx = (int)(substr($year,-4));
    if (($yearx < 1990) || ($yearx > 2020)) die("Year: '$year'");
    return $yearx;
  }
}
?>

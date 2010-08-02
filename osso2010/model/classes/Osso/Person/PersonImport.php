<?php
class Osso_Person_PersonImport extends Cerad_Import
{
  protected $readerClassName = 'Osso_Person_PersonReader';
  protected $regions = array();

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso;
    $this->ts = $this->context->getTimeStamp();

    $this->directRegMainEayso = new Eayso_Reg_Main_RegMainDirect($this->context);
    $this->directRegMainOsso  = new  Osso_Reg_Main_RegMainDirect($this->context);
    
    $this->directRegProp      = new  Osso_Reg_Prop_RegPropDirect($this->context);
    $this->directRegOrg       = new  Osso_Reg_Org_RegOrgDirect  ($this->context);

    $this->directPerson       = new Osso_Person_PersonDirect              ($this->context);
    $this->directPersonReg    = new Osso_Person_Reg_PersonRegDirect       ($this->context);

    //$this->directPersonRegOrg = new Osso_Person_Reg_Org_PersonRegOrgDirect($this->context);

    $this->directOrg = new Osso_Org_OrgDirect($this->context);
    
  }
  protected $regTypeOsso = 101;
  protected $regTypeAyso = 102;

  public function getResultMessage()
  {
    $file = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u",
      $class, $file,
      $count->total,$count->inserted,$count->updated);
    return $msg;
  }
  public function processRegMain($data,$dataRegMainEayso)
  {
    $personId = $data['id'];

    // See if have osso record
    $search = array('reg_type' => $this->regTypeOsso,'reg_num' => $personId);
    $result = $this->directRegMainOsso->fetchRow($search);
    if (!$result->row)
    {
      // Use eayso data
      $datax = $dataRegMainEayso;
      unset($datax['id']);
      $datax['reg_type'] = $this->regTypeOsso;
      $datax['reg_num']  = $personId;

      // Xfer over any changes
      $fields = array('fname','lname','nname','mname');
      foreach($fields as $key)
      {
        if ($data[$key]) $datax[$key] = $data[$key];
      }
      $this->directRegMainOsso->insert($datax);
      $this->count->inserted++;
      return;
    }
    // TODOx Update if have changes
    return;

  }
  public function processRegOrg($data)
  {
    $region = $data['region'];
    if (!$region) return;

    if (!isset($this->regions[$region]))
    {
      $result = $this->directOrg->getOrgForKey($region);
      if (!$result->row)
      {
        echo "*** Missing region $region\n";
        $this->regions[$region] = 0;
        return;
      }
      $this->regions[$region] = $result->row['id'];
    }
    $orgId = $this->regions[$region];
    if (!$orgId) return;

    $datax = array(
      'reg_type' => $this->regTypeOsso,
      'reg_num'  => $data['id'],
      'org_id'   => $orgId
    );
    $this->directRegOrg->insert($datax);

  }
  protected function processRegProp($data)
  {
    $propTypes = array(
      'phone_home' => 11,
      'phone_work' => 12,
      'phone_cell' => 13,
      'email_home' => 21,
      'email_work' => 22,
    );
    $datax = array(
      'reg_type' => $this->regTypeOsso,
      'reg_num'  => $data['id'],
    );
    foreach($propTypes as $name => $typex)
    {
      if ($data[$name])
      {
        $valuex = $data[$name];

        switch($typex)
        {
          case 11: case 12: case 13:
              $valuex = preg_replace('/\D/','',$valuex);
            break;
        }
        $datax['typex']  = $typex;
        $datax['valuex'] = $valuex;
        $this->directRegProp->insert($datax); // TODOx DUP key should update valuex
      }
    }
  }
  public function processEaysoVolunteer($data)
  {
    // Make sure have one
    $aysoid = $data['aysoid'];
    if (!$aysoid) return 0;

    // Verify it's correct
    $search = array('reg_type' => $this->regTypeAyso,'reg_num' => $aysoid);
    $result = $this->directRegMainEayso->fetchRow($search);
    if (!$result->row)
    {
      // echo "Missing or invalid aysoid: $aysoid\n";
      return 0;
    }
    $dataRegMainEayso = $result->row;

    // Add a main record
    $personId   = $data['id'];
    $this->directPerson->insert(array('id' => $personId));

    // Add person_reg record for eayso
    $datax = array(
      'person_id' => $personId,
      'reg_type'  => $dataRegMainEayso['reg_type'],
      'reg_num'   => $dataRegMainEayso['reg_num'],
    );
    $this->directPersonReg->insert($datax);

    // Add person_reg record for osso
    $datax = array(
      'person_id' => $personId,
      'reg_type'  => $this->regTypeOsso,
      'reg_num'   => $personId,
    );
    $this->directPersonReg->insert($datax);

    // Process Reg Main
    $this->processRegMain($data,$dataRegMainEayso);
    $this->processRegProp($data);
    $this->processRegOrg ($data);
    
// die('Got here');

    // Fully processed
    return 1;
  }
  public function processRowData($data)
  {   
    // Validation
    if (!$data['id']) return;
    $this->count->total++;

    // Handle eayso volunteers
    if ($this->processEaysoVolunteer($data)) return;

    // Special checks, only processing those with eayso ids for now
    return;

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

    if (!$region) return;
    
    // Need to find the org_id for the region
    if (!isset($this->regions[$region]))
    {
      $search = array('keyx' => $region);
      $result = $this->directOrg->getOrgForKey($search);

      $org = $result->row;
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

    // ok to just insert as dups will be ignored
    $row = array('org_id' => $orgId, 'person_reg_id' => $personRegId);
    $this->directPersonRegOrg->insert($row);

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

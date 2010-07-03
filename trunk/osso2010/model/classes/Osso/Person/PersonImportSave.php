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

    $this->count->insertedVolRegion     = 0;
    $this->count->updatedPersonRegEayso = 0;
    $this->count->insertedPersonRegOsso = 0;

    $this->directPersonRegEayso = new Eayso_Person_Reg_PersonRegDirect($this->context);

    $this->directPerson       = new Osso_Person_PersonDirect              ($this->context);
    $this->directPersonReg    = new Osso_Person_Reg_PersonRegDirect       ($this->context);
    $this->directPersonRegOrg = new Osso_Person_Reg_Org_PersonRegOrgDirect($this->context);

    $this->directOrg = new Osso_Org_OrgDirect($this->context);
    
  }
  public function getResultMessage()
  {
    $file = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u, PersonReg Inserted: %u, Org Inserted: %u",
      $class, $file,
      $count->total,$count->inserted,$count->updated,$count->insertedPersonRegOsso, $count->insertedVolRegion);
    return $msg;
  }
  public function processEaysoVolunteer($data)
  {
    $db = $this->db;

    // Make sure have one
    $aysoid = $data['aysoid'];
    if (!$aysoid) return 0;

    // Verify it's correct
    $search = array(
      'person_reg_type_id' => 2,
      'person_reg_num'     => $aysoid
    );
    $result = $this->directPersonRegEayso->fetchRow($search);
    if (!$result->row)
    {
      // echo "Missing or invalid aysoid: $aysoid\n";
      return 0;
    }
    $dataPersonRegEayso = $result->row;

    // Set insert or update main record
    $personId   = $data['id'];
    $result = $this->directPerson->fetchRow(array('id' => $personId));

    $dataPerson = $result->row;

    if (!$dataPerson)
    {
      // Start with eayso info
      $fields = array('fname','lname','nname','mname','sname','dob','gender',);
      $datax = array();
      foreach($fields as $field) { $datax[$field] = $dataPersonRegEayso[$field]; }

      // Override with reader info, initial import use eayso data always
      $fields = array('fname','lname','nname','mname');
      foreach($fields as $field)
      {
        if ($data[$field] && !$datax[$field]) $datax[$field] = $data[$field]; 
      }

      $personId = $data['id'];
      if ($personId) $datax['id'] = $personId;

      $datax['status']     = 1;
      $datax['ts_created'] = $this->ts;
      $datax['ts_updated'] = $this->ts;

      $result = $this->directPerson->insert($datax);
      
      $personId = $result->id;
      $this->count->inserted++;

      // Need for osso person_reg entry
      $dataPerson = $datax;
      $dataPerson['id'] = $personId;
    }
    else
    {
      // Update if necessary
      $changes = array();
      foreach($data as $key => $value)
      {
        if ($value && isset($personData[$key]) && ($personData[$key] != $value)) $changes[$key] = $value;
      }
      // Revisit after initial import is complete
      $changes = array();
      if (count($changes))
      {
        //Cerad_Debug::dump($changes); die();
        $changes['id'] = $personId;
        $changes['ts_updated'] = $this->ts;
        $this->directPerson->update($changes);
        $this->count->updated++;
      }
    }

    // See if need to update person_id for the eayso record
    if ($personId != $dataPersonRegEayso['person_id'])
    {
      if ($dataPersonRegEayso['person_id'])
      {
        $msg = sprintf("*** Two persons with same aysoid: %s %u %u",$aysoid, $personId,$dataPersonRegEayso);
        echo $msg . "\n";
        return 1;
      }
      $changes['person_id']  = $personId;
      $changes['ts_updated'] = $this->ts;
      $changes['id']         = $dataPersonRegEayso['id'];

      // This is an update I'd like to make go away
      $this->directPersonRegEayso->update($changes);

      $this->count->updatedPersonRegEayso++;
    }
    // Insert person_reg record if needed
    $search = array('person_reg_type_id' => 1, 'person_reg_num' => $personId);
    $result = $this->directPersonReg->fetchRow($search);

    $dataPersonRegOsso = $result->row;

    if (!$dataPersonRegOsso)
    {
      $datax = array();

      $datax['person_id']          = $personId;
      $datax['person_reg_num']     = $personId;
      $datax['person_reg_year']    = $dataPersonRegEayso['person_reg_year'];
      $datax['person_reg_type_id'] = 1;
     
      $fields = array('fname','lname','nname','mname','sname','dob','gender',);
      foreach($fields as $field) { $datax[$field] = $dataPerson[$field]; }

      $fields = array('phone_home','phone_work','phone_cell','email','email2');
      foreach($fields as $field)
      {
        $datax[$field] = $dataPersonRegEayso[$field];

        // Attempt to update
        if ($data[$field] && !$datax[$field]) $datax[$field] = $data[$field];
      }
      $datax['ts_created'] = $this->ts;
      $datax['ts_updated'] = $this->ts;

      $result = $this->directPersonReg->insert($datax);
      $this->count->insertedPersonRegOsso++;

      $dataPersonRegOsso = $datax;
      $dataPersonRegOsso['id'] = $result->id;
    }
    else
    {
      // TODOx Implement updating later
    }
    // Store a local copy of just the eayso reg_num
    $search = array('person_reg_type_id' => 2, 'person_reg_num' => $aysoid);
    $result = $this->directPersonReg->fetchRow($search);
    $row = $result->row;
    if (!$row)
    {
      $row['person_reg_type_id'] = 2;
      $row['person_reg_num']     = $aysoid;
      $row['person_id']          = $personId;
      $this->directPersonReg->insert($row);
    }
    else
    {
      // Check that person_id is valid
      if ($row['person"id'] != $personId)
      {
        echo "*** person is mistmatch {$aysoid} {$personId}\n";
      }
    }
    // Link region to person
    $this->processVolRegion($dataPersonRegOsso['id'],$data['region']);

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

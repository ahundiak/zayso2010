<?php
class Osso_Team_Phy_PhyTeamImport2 extends Cerad_Import
{
  protected $readerClassName = 'Osso_Team_Phy_PhyTeamReader';
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
  public function getPerson($regionId,$fname,$lname)
  {
    // Need some data
    if (!$regionId) return 0;
    if (!$fname)    return 0;
    if (!$lname)    return 0;

    $result = $this->directRegMainEayso->getForOrgName($regionId,$fname,$lname);
    $rows = $result->rows;
    if (count($rows) < 1)
    {
      printf("*** Person not found %d %s %s\n",$regionId,$fname,$lname);
      die();
    }
    if (count($rows) > 1)
    {
      printf("*** Multiple people found %d %s %s\n",$regionId,$fname,$lname);
      //die();
    }
    $row = $rows[0];

    // Need to find the person record

    //printf("Found %s %s\n",$fname,$lname);
    
    return 0;
  }
  public function processRowData($data)
  {   
    // Validation
    if (!$data['teamId']) return;
    $this->count->total++;

    // Mess with the key
    $teamDes = $data['teamDes'];
    $teamKey = $data['teamKey'];

    $teamDes  = str_replace('-','',$teamDes);
    $teamDess = explode(' ',$teamDes);
    $teamDes  = $teamDess[0];

    $teamKey = $this->getTeamKey($teamDes);
    if (!$teamKey) return;

    if ($teamKey != $data['teamKey'])
    {
      printf("*** Team Key %s %s %s\n",$teamDes,$teamKey,$data['teamKey']);
    }

    // Get the region
    $regionId = $this->getRegion($data['region']);
    if (!$regionId) return;

    // Get volunteers
    $vols = array();
    $vols[10] = $this->getPerson($regionId,$data['headCoachFName'],$data['headCoachLName']);
    $vols[11] = $this->getPerson($regionId,$data['asstCoachFName'],$data['asstCoachLName']);
    $vols[12] = $this->getPerson($regionId,$data['managerFName'  ],$data['managerLName'  ]);

    // Handle eayso volunteers
    // if ($this->processEaysoVolunteer($data)) return;

    // Special checks, only processing those with eayso ids for now
    return;
  }
  protected function getRegion($region)
  {

    if (!$region) return NULL;

    // Need to find the org_id for the region
    if (!isset($this->regions[$region]))
    {
      $result = $this->directOrg->getOrgForKey($region);

      $org = $result->row;
      if (!$org)
      {
        echo("Could not find region: $region\n"); // Some regions are revoked
        $this->regions[$region] = 0;
        return 0;
      }
      
      $this->regions[$region] = $org['id'];
    }
    return $this->regions[$region];
  }
  protected function getTeamKey($teamDes)
  {
    foreach($this->divs as $divKey => $div)
    {
      $len = strlen($divKey);
      if (substr($teamDes,0,$len) == $divKey)
      {
        $age = $div['age'];
        $sex = $div['sex'];
        $seq = (int)substr($teamDes,$len);

        if ($age == 0) return NULL;

        $teamKey = sprintf('U%02u%s%02u',$age,$sex,$seq);
        return $teamKey;
      }
     }
     echo "*** Bad Division Key '$teamDes'\n";
     return NULL;
  }
  protected $divs = array
  (
    'U5C'  => array('age' =>  5, 'sex' => 'C'),
    'U5B'  => array('age' =>  5, 'sex' => 'B'),
    'U5G'  => array('age' =>  5, 'sex' => 'G'),

    'U05C' => array('age' =>  5, 'sex' => 'C'),
    'U05B' => array('age' =>  5, 'sex' => 'B'),
    'U05G' => array('age' =>  5, 'sex' => 'G'),

    'U6C'  => array('age' =>  6, 'sex' => 'B'),
    'U6B'  => array('age' =>  6, 'sex' => 'B'),
    'U6G'  => array('age' =>  6, 'sex' => 'G'),

    'U06C' => array('age' =>  6, 'sex' => 'B'),
    'U06B' => array('age' =>  6, 'sex' => 'B'),
    'U06G' => array('age' =>  6, 'sex' => 'G'),

    'U7C'  => array('age' =>  7, 'sex' => 'B'),
    'U7B'  => array('age' =>  7, 'sex' => 'B'),
    'U7G'  => array('age' =>  7, 'sex' => 'G'),

    'U07C' => array('age' =>  7, 'sex' => 'B'),
    'U07B' => array('age' =>  7, 'sex' => 'B'),
    'U07G' => array('age' =>  7, 'sex' => 'G'),

    'U8C'  => array('age' =>  8, 'sex' => 'B'),
    'U8B'  => array('age' =>  8, 'sex' => 'B'),
    'U8G'  => array('age' =>  8, 'sex' => 'G'),

    'U08C' => array('age' =>  8, 'sex' => 'B'),
    'U08B' => array('age' =>  8, 'sex' => 'B'),
    'U08G' => array('age' =>  8, 'sex' => 'G'),

    'U10C' => array('age' => 10, 'sex' => 'B'),
    'U10B' => array('age' => 10, 'sex' => 'B'),
    'U10G' => array('age' => 10, 'sex' => 'G'),

    'U12C' => array('age' => 12, 'sex' => 'B'),
    'U12B' => array('age' => 12, 'sex' => 'B'),
    'U12G' => array('age' => 12, 'sex' => 'G'),

    'U14C' => array('age' => 14, 'sex' => 'B'),
    'U14B' => array('age' => 14, 'sex' => 'B'),
    'U14G' => array('age' => 14, 'sex' => 'G'),

    'U16C' => array('age' => 16, 'sex' => 'B'),
    'U16B' => array('age' => 16, 'sex' => 'B'),
    'U16G' => array('age' => 16, 'sex' => 'G'),

    'U19C' => array('age' => 19, 'sex' => 'B'),
    'U19B' => array('age' => 19, 'sex' => 'B'),
    'U19G' => array('age' => 19, 'sex' => 'G'),

    'VIP'  => array('age' =>  0, 'sex' => 'C'),
  );
}
?>

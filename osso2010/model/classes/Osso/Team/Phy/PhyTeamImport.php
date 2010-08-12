<?php
class Osso_Team_Phy_PhyTeamImport extends Cerad_Import
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

    $this->directPhyTeam = new Osso_Team_Phy_PhyTeamDirect($this->context);
    $this->directSchTeam = new Osso_Team_Sch_SchTeamDirect($this->context);

    $this->directPhyTeamPerson = new Osso_Team_Phy_PhyTeamPersonDirect($this->context);
    
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

  // Finds the eayso volunteer, adding it to person ig necessary
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
      return 0;
    }
    if (count($rows) > 1)
    {
      // printf("*** Multiple people found %d %s %s\n",$regionId,$fname,$lname);
      //die();
      return 0;
    }
    $dataRegMainEayso = $rows[0];

    // Cerad_Debug::dump($dataRegMainEayso); die();

    // Need to find the person record
    $result = $this->directPersonReg->getForAysoid($dataRegMainEayso['reg_num']);
    $dataPersonReg = $result->row;
    if (isset($dataPersonReg['person_id']))
    {
      // printf("Found %s %s %s %d\n",$fname,$lname,$eaysoMainData['reg_num'],$personRegData['person_id']);
      return $dataPersonReg['person_id'];
    }
    // Add new person record
    $result = $this->directPerson->insert(array());
    $personId = $result->id;

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

    // Match to organization
    $datax = array(
      'reg_type' => $this->regTypeOsso,
      'reg_num'  => $personId,
      'org_id'   => $regionId
    );
    $this->directRegOrg->insert($datax);

    // The main data
    $datax = $dataRegMainEayso;
    unset($datax['id']);
    $datax['reg_type'] = $this->regTypeOsso;
    $datax['reg_num']  = $personId;

    $this->directRegMainOsso->insert($datax);

    // printf("Added %s %s %s %d\n",$fname,$lname,$dataRegMainEayso['reg_num'],$personId);
    
    return $personId;
  }
  protected function savePhyTeam($data,$regionId,$teamKey)
  {
    // Gather up the data
    $datax = array();
    $datax['id']       = (int)$data['teamId'];
    $datax['team_id']  = (int)$data['teamId'];

    $datax['team_des'] = $data['teamDes'];
    $datax['team_key'] = $teamKey;

    $datax['name']     = $data['teamName'];
    $datax['colors']   = $data['teamColors'];

    $datax['org_id']      = $regionId;
    $datax['cal_year']    = 2010;
    $datax['sea_type_id'] = 1;
    $datax['age']         = (int)substr($teamKey,1,2);
    $datax['sex']         =      substr($teamKey,3,1);
    $datax['num']         = (int)substr($teamKey,4,2);

    // Have one already?
    $result = $this->directPhyTeam->fetchRow(array('id' => $datax['id']));
    if ($result->row)
    {
      $this->directPhyTeam->update($datax);
      return;
    }
    // New record
    $this->directPhyTeam->insert($datax);
    $this->count->inserted++;
    
    return;
  }
  protected function saveSchTeam($data,$regionId,$teamKey)
  {
    // Gather up the data
    $datax = array();
    $datax['id']           = (int)$data['teamId'];
    $datax['team_phy_id']  = (int)$data['teamId'];

    $datax['org_id']      = $regionId;
    $datax['cal_year']    = 2010;
    $datax['sch_type_id'] = 1;
    $datax['sea_type_id'] = 1;
    $datax['age']         = (int)substr($teamKey,1,2);
    $datax['sex']         =      substr($teamKey,3,1);
    $datax['sortx']       = (int)substr($teamKey,4,2);

    // Have one already?
    $result = $this->directSchTeam->fetchRow(array('id' => $datax['id']));
    if ($result->row)
    {
      $this->directSchTeam->update($datax);
      return;
    }
    // New record
    $this->directSchTeam->insert($datax);

    // $this->count->inserted++;

    return;
  }
  public function processRowData($data)
  {   
    // Validation
    if (!$data['teamId']) return;
    $this->count->total++;

    // Build a normalized team key
    $teamId  = $data['teamId'];
    $teamDes = $data['teamDes'];

    $teamDes  = str_replace('-','', $teamDes);
    $teamDes  = str_replace('_',' ',$teamDes);
    $teamDess = explode(' ',$teamDes);
    $teamDes  = $teamDess[0];

    $teamKey = $this->getTeamKey($teamDes);
    if (!$teamKey) return;

    // Get the region
    $regionId = $this->getRegion($data['region']);
    if (!$regionId) return;

    // And save
    $this->savePhyTeam($data,$regionId,$teamKey);
    $this->saveSchTeam($data,$regionId,$teamKey);

    // Get volunteers based on names
    $vols = array();
    $vols[10] = $this->getPerson($regionId,$data['headCoachFName'],$data['headCoachLName']);
    $vols[11] = $this->getPerson($regionId,$data['asstCoachFName'],$data['asstCoachLName']);
    $vols[12] = $this->getPerson($regionId,$data['managerFName'  ],$data['managerLName'  ]);

    // Easiest way to stay in sync
    $this->directPhyTeamPerson->deleteForPhyTeam($teamId);

    // Add back in
    foreach($vols as $typeId => $personId)
    {
      if ($personId)
      {
        // Insert new
        $datax['person_id']   = $personId;
        $datax['team_phy_id'] = $teamId;
        $datax['type_id']     = $typeId;
        $this->directPhyTeamPerson->insert($datax);
      }
    }
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

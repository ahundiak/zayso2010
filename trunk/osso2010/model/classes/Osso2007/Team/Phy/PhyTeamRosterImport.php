<?php
class Osso2007_Team_Phy_PhyTeamRosterImport extends Cerad_Import
{
  protected $readerClassName = 'Osso_Team_Phy_PhyTeamRosterReader';
  protected $regions = array();
  protected $teams   = array();

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso;
    $this->ts = $this->context->getTimeStamp();

    $this->directRegMainEayso = new Eayso_Reg_Main_RegMainDirect($this->context);
    $this->directRegMainOsso  = new  Osso_Reg_Main_RegMainDirect($this->context);
    
    //$this->directRegProp      = new  Osso_Reg_Prop_RegPropDirect($this->context);
    //$this->directRegOrg       = new  Osso_Reg_Org_RegOrgDirect  ($this->context);

    $this->directPerson       = new Osso2007_Person_PersonDirect              ($this->context);

    //$this->directPersonReg    = new Osso_Person_Reg_PersonRegDirect       ($this->context);
    //$this->directPersonRegOrg = new Osso_Person_Reg_Org_PersonRegOrgDirect($this->context);

    $this->directOrg = new Osso_Org_OrgDirect($this->context);

    $this->directPhyTeam = new Osso2007_Team_Phy_PhyTeamDirect($this->context);
    $this->directSchTeam = new Osso2007_Team_Sch_SchTeamDirect($this->context);

    $this->directPhyTeamPerson = new Osso2007_Team_Phy_PhyTeamPersonDirect($this->context);
    
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

  // Finds the eayso volunteer, adding it to person if necessary
  public function getPerson($regionId,$aysoid)
  {
    // Need some data
    if (!$regionId) return 0;
    if (!$aysoid)   return 0;
    
    $result = $this->directRegMainEayso->fetchRow(array('reg_num' => $aysoid));
    $row = $result->row;
    if (!$row)
    {
      printf("*** Eayso Record not found %d %s\n",$regionId,$aysoid);
      return 0;
    }
    $dataRegMainEayso = $row;

    // Cerad_Debug::dump($dataRegMainEayso); die();

    // Need to find the person record
    $result = $this->directPerson->fetchRow(array('aysoid' => $dataRegMainEayso['reg_num']));
    $dataPerson = $result->row;
    if (isset($dataPerson['person_id']))
    {
      // printf("Found %s %s %s %d\n",$fname,$lname,$eaysoMainData['reg_num'],$personRegData['person_id']);
      return $dataPerson['person_id'];
    }
    // Add new person record
    $datax = array(
      'fname'   => $dataRegMainEayso['fname'],
      'lname'   => $dataRegMainEayso['lname'],
      'mname'   => $dataRegMainEayso['mname'],
      'nname'   => $dataRegMainEayso['nname'],
      'unit_id' => $regionId,
      'aysoid'  => $dataRegMainEayso['reg_num'],
      'status'  => 3,
    );
    $result = $this->directPerson->insert($datax);

    return $result->id;
  }
  protected function getPhyTeam($regionId,$teamDes)
  {
    $search = array(
      'unit_id'        => $regionId,
      'eayso_des'      => $teamDes,
      'reg_year_id'    => 10,
      'season_type_id' => 1,
    );
    $result = $this->directPhyTeam->fetchRow($search);
    if ($result->row) return $result->row['phy_team_id'];
    return 0;
  }
  public function processRowData($data)
  {   
    // Validation
    $teamDes = $data['teamDes'];
    if (!$teamDes) return;
    if ($teamDes == 'VIP') return;

    if (isset($this->teams[$teamDes])) return;
    $this->teams[$teamDes] = true;
    
    $this->count->total++;

    // Mess with the key

    // Get the region
    $regionId = $this->getRegion($data['region']);
    if (!$regionId) die('Invalid region id ' . $data['region']); // return;

    // Physical team should always exist
    $phyTeamId = $this->getPhyTeam($regionId,$teamDes);
    if (!$phyTeamId) die('No existing physical team for ' . $teamDes);

    // Get volunteers based on aysoid
    $vols = array();
    $vols[16] = $this->getPerson($regionId,$data['headCoachAysoid']);
    $vols[17] = $this->getPerson($regionId,$data['asstCoachAysoid']);
    $vols[18] = $this->getPerson($regionId,$data['managerAysoid']);

    // Easiest way to stay in sync
    $this->directPhyTeamPerson->deleteForPhyTeam($phyTeamId);
/*
    $result = $this->directPhyTeamPerson->fetchRows(array('phy_team_id' => $phyTeamId));
    $volsx = array(16 => 0, 17 => 0, 18 => 0);
    foreach($results->rows as $row)
    {
      if (isset($volsx[$row['vol_type_id']])) $volsx[$row['vol_type_id']] = $row['person_id'];
    }
 */
    // Add back in
    foreach($vols as $typeId => $personId)
    {
      if ($personId)
      {
        // Insert new
        $datax['person_id']   = $personId;
        $datax['phy_team_id'] = $phyTeamId;
        $datax['vol_type_id'] = $typeId;
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
}
?>

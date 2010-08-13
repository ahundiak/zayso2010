<?php
class Osso2007_Team_Phy_PhyTeamImport extends Cerad_Import
{
  protected $readerClassName = 'Osso_Team_Phy_PhyTeamReader';
  protected $regions = array();

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso;
    $this->ts = $this->context->getTimeStamp();

    $this->directRegMainEayso = new Eayso_Reg_Main_RegMainDirect($this->context);

    $this->directPerson  = new Osso2007_Person_PersonDirect($this->context);

    $this->directOrg     = new Osso_Org_OrgDirect($this->context);

    $this->directVol     = new Osso2007_Vol_VolDirect($this->context);

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
  protected function getPersonForName($regionId,$fname,$lname)
  {
    // Need some data
    if (!$regionId) return 0;
    if (!$fname)    return 0;
    if (!$lname)    return 0;

    $result = $this->directRegMainEayso->getForOrgName($regionId,$fname,$lname);
    $rows = $result->rows;
    if (count($rows) < 1)
    {
      // printf("*** Person not found %d %s %s\n",$regionId,$fname,$lname);
      return 0;
    }
    if (count($rows) > 1)
    {
      // printf("*** Multiple people found %d %s %s\n",$regionId,$fname,$lname);
      //die();
      return 0;
    }
    return $this->getPersonForAysoid($regionId,$rows[0]['reg_num']);
  }
  protected function getPersonForAysoid($regionId,$aysoid)
  {
    // Cerad_Debug::dump($data); die();
    if (!$regionId) return 0;
    if (!$aysoid)   return 0;

    // Need to find the person record
    $result = $this->directPerson->fetchRow(array('aysoid' => $aysoid));
    $dataPerson = $result->row;
    if (isset($dataPerson['person_id']))
    {
      // printf("Found %s %s %s %d\n",$fname,$lname,$eaysoMainData['reg_num'],$personRegData['person_id']);
      return $dataPerson['person_id'];
    }
    // Lookup ayso record
    $result = $this->directRegMainEayso->fetchRow(array('reg_num' => $aysoid));
    if (!$result->row) return 0;
    $dataRegMainEayso = $result->row;

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
  protected function savePhyTeam($data,$regionId,$teamKey)
  {
    // Gather up the data
    $datax = array();
    $datax['eayso_id']  = (int)$data['teamId'];
    $datax['eayso_des'] = $data['teamDes'];

    $datax['name']     = $data['teamName'];
    $datax['colors']   = $data['teamColors'];

    $datax['unit_id']          = $regionId;
    $datax['reg_year_id']      = 10;
    $datax['season_type_id']   = 1;
    $datax['division_id']      = $this->divs[substr($teamKey,0,4)]['id'];
    $datax['division_seq_num'] = (int)substr($teamKey,4,2);

    // Have one already?
    $result = $this->directPhyTeam->fetchRow(array('eayso_id' => $data['teamId']));
    if ($result->row)
    {
      $id = $result->row['phy_team_id'];
      $datax['phy_team_id'] = $id;
      $this->directPhyTeam->update($datax);
      return $datax;
    }
    // New record
    $result = $this->directPhyTeam->insert($datax);
    $datax['phy_team_id'] = $result->id;

    $this->count->inserted++;

//  Cerad_Debug::dump($data);
//  Cerad_Debug::dump($datax);

    return $datax;
  }
  protected function saveSchTeam($data)
  {
    // Gather up the data
    $datax = array();
    $datax['phy_team_id']      = $data['phy_team_id'];
    $datax['unit_id']          = $data['unit_id'];
    $datax['reg_year_id']      = $data['reg_year_id'];
    $datax['season_type_id']   = $data['season_type_id'];
    $datax['schedule_type_id'] = 1;
    $datax['division_id']      = $data['division_id'];
    $datax['sortx']            = $data['division_seq_num'];
    $datax['desc_short']       = '';

    // Have one already?
    $result = $this->directSchTeam->fetchRow(array('phy_team_id' => $datax['phy_team_id']));
    if ($result->row)
    {
      $id = $result->row['sch_team_id'];
      $datax['sch_team_id'] = $id;
      $this->directSchTeam->update($datax);
      return;
    }
    // New record
    $result = $this->directSchTeam->insert($datax);

    // die('Inserted sch team ' . $result->id);
    // $this->count->inserted++;

    return;
  }
  public function processRowData($data)
  {   
    // Validation
    if (!$data['teamId']) return;
    $this->count->total++;

    // Mess with the key
    $teamId  = $data['teamId'];
    $teamDes = $data['teamDes'];

    $teamDes  = str_replace('FAY','', $teamDes);
    $teamDes  = str_replace('SL', '', $teamDes);

    $teamDes  = str_replace('R160-','', $teamDes);
    $teamDes  = str_replace('160-', '', $teamDes);

    $teamDes  = str_replace('-','', $teamDes);
    $teamDes  = str_replace('_',' ',$teamDes);
    $teamDess = explode(' ',$teamDes);
    $teamDes  = $teamDess[0];

    $teamKey = $this->getTeamKey($teamDes);
  //printf("Key %s\n",$teamKey); return;
    if (!$teamKey) return;

    // Get the region
    $regionId = $this->getRegion($data['region']);
    if (!$regionId) die('Invalid region id ' . $data['region']); // return;

    // And save
    $phyTeamData = $this->savePhyTeam($data,$regionId,$teamKey);
    $this->saveSchTeam($phyTeamData);

    // Get volunteers based on names
    $persons = array
    (
      array('type_id' => 16, 'fname' => 'headCoachFName', 'lname' => 'headCoachLName'),
      array('type_id' => 17, 'fname' => 'asstCoachFName', 'lname' => 'asstCoachLName'),
      array('type_id' => 18, 'fname' => 'managerFName',   'lname' => 'managerLName'),
    );
    $vols = array();
    foreach($persons as $person)
    {
      $personId = $this->getPersonForName($regionId,$data[$person['fname']],$data[$person['lname']]);
      if ($personId) $vols[$person['type_id']] = $personId;
    }
    return $this->insertPhyTeamPersons($phyTeamData,$vols);
  }
  protected function insertPhyTeamPersons($phyTeamData,$vols)
  {
    $phyTeamId = $phyTeamData['phy_team_id'];
    
    // Easiest way to stay in sync
    // $this->directPhyTeamPerson->deleteForPhyTeam($teamId);
    
    // Grab any existing records
    $result = $this->directPhyTeamPerson->fetchRows(array('phy_team_id' => $phyTeamId));
    $volsx = array();
    foreach($result->rows as $row)
    {
      $volsx[$row['vol_type_id']] = $row;
    }
    // Add back in
    foreach($vols as $typeId => $personId)
    {
      if (!isset($volsx[$typeId]))
      {
        // Insert new
        $datax = array();
        $datax['person_id']   = $personId;
        $datax['phy_team_id'] = $phyTeamId;
        $datax['vol_type_id'] = $typeId;
        $this->directPhyTeamPerson->insert($datax);
      }
      else // Have record for vol type
      {
        $datax = $volsx[$typeId];
        if ($datax['person_id'] != $personId)
        {
          $datax['person_id'] = $personId;
          $this->directPhyTeamPerson->update($datax);
        }
      }
      // Always update the vol table except no unique key
      $datax = array();
      $datax['person_id']   = $personId;
      $datax['vol_type_id'] = $typeId;

      $datax['reg_year_id']    = $phyTeamData['reg_year_id'];
      $datax['season_type_id'] = $phyTeamData['season_type_id'];
      $datax['unit_id']        = $phyTeamData['unit_id'];
      $datax['division_id']    = $phyTeamData['division_id'];
      $result = $this->directVol->fetchRows($datax);
      if (!$result->rowCount) $this->directVol->insert($datax);
      
    }
    // Maybe should do one more loop for any deleted items
    foreach($volsx as $volx)
    {
      if (!isset($vols[$volx['vol_type_id']]))
      {
        $this->directPhyTeamPerson->delete($volx['phy_team_person_id']);
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

    'U5C'  => array('id' => 24, 'age' =>  5, 'sex' => 'C'),
    'U5B'  => array('id' => 22, 'age' =>  5, 'sex' => 'B'),
    'U5G'  => array('id' => 23, 'age' =>  5, 'sex' => 'G'),
    'U5'   => array('id' => 24, 'age' =>  5, 'sex' => 'C'),
    
    'U05C' => array('id' => 24, 'age' =>  5, 'sex' => 'C'),
    'U05B' => array('id' => 22, 'age' =>  5, 'sex' => 'B'),
    'U05G' => array('id' => 23, 'age' =>  5, 'sex' => 'G'),

    'U6C'  => array('id' =>  3, 'age' =>  6, 'sex' => 'B'),
    'U6B'  => array('id' =>  1, 'age' =>  6, 'sex' => 'B'),
    'U6G'  => array('id' =>  2, 'age' =>  6, 'sex' => 'G'),
    'U6'   => array('id' =>  3, 'age' =>  6, 'sex' => 'C'),

    'U06C' => array('id' =>  3, 'age' =>  6, 'sex' => 'B'),
    'U06B' => array('id' =>  1, 'age' =>  6, 'sex' => 'B'),
    'U06G' => array('id' =>  2, 'age' =>  6, 'sex' => 'G'),

    'U7C'  => array('id' => 27, 'age' =>  7, 'sex' => 'B'),
    'U7B'  => array('id' => 25, 'age' =>  7, 'sex' => 'B'),
    'U7G'  => array('id' => 26, 'age' =>  7, 'sex' => 'G'),

    'U07C' => array('id' => 27, 'age' =>  7, 'sex' => 'B'),
    'U07B' => array('id' => 25, 'age' =>  7, 'sex' => 'B'),
    'U07G' => array('id' => 26, 'age' =>  7, 'sex' => 'G'),

    'U8C'  => array('id' =>  6, 'age' =>  8, 'sex' => 'B'),
    'U8B'  => array('id' =>  4, 'age' =>  8, 'sex' => 'B'),
    'U8G'  => array('id' =>  5, 'age' =>  8, 'sex' => 'G'),

    'U08C' => array('id' =>  6, 'age' =>  8, 'sex' => 'B'),
    'U08B' => array('id' =>  4, 'age' =>  8, 'sex' => 'B'),
    'U08G' => array('id' =>  5, 'age' =>  8, 'sex' => 'G'),

    'U10C' => array('id' =>  9, 'age' => 10, 'sex' => 'B'),
    'U10B' => array('id' =>  7, 'age' => 10, 'sex' => 'B'),
    'U10G' => array('id' =>  8, 'age' => 10, 'sex' => 'G'),

    'U12C' => array('id' => 12, 'age' => 12, 'sex' => 'B'),
    'U12B' => array('id' => 10, 'age' => 12, 'sex' => 'B'),
    'U12G' => array('id' => 11, 'age' => 12, 'sex' => 'G'),

    'U14C' => array('id' => 15, 'age' => 14, 'sex' => 'B'),
    'U14B' => array('id' => 13, 'age' => 14, 'sex' => 'B'),
    'U14G' => array('id' => 14, 'age' => 14, 'sex' => 'G'),

    'U16C' => array('id' => 18, 'age' => 16, 'sex' => 'B'),
    'U16B' => array('id' => 16, 'age' => 16, 'sex' => 'B'),
    'U16G' => array('id' => 17, 'age' => 16, 'sex' => 'G'),

    'U19C' => array('id' => 21, 'age' => 19, 'sex' => 'B'),
    'U19B' => array('id' => 19, 'age' => 19, 'sex' => 'B'),
    'U19G' => array('id' => 20, 'age' => 19, 'sex' => 'G'),

    'VIP'  => array('age' =>  0, 'sex' => 'C'),
  );
}
?>

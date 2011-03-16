<?php
class Osso2007_Team_Phy_PhyTeamImportReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'TeamDesignation' => 'teamDes',
    'TeamID'          => 'teamId',

    'DivisionName'    => 'divName',
    'RegionNumber'    => 'org',

    'TeamCoachFName'      => 'headCoachFName',
    'TeamCoachLName'      => 'headCoachLName',
    'TeamAsstCoachFName'  => 'asstCoachFName',
    'TeamAsstCoachLName'  => 'asstCoachLName',
    'TeamParentFName'     => 'managerFName',
    'TeamParentLName'     => 'managerLName',

    'TeamName'            => 'teamName',
    'TeamColors'          => 'teamColors',
  );
  protected $mapOptional = array
  (
      'TeamKey' => 'teamKey',
  );
}
class Osso2007_Team_Phy_PhyTeamImport extends Cerad_Import
{
  protected $readerClassName = 'Osso2007_Team_Phy_PhyTeamImportReader';
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

    $repos = $this->context->repos;

    $this->repoDiv = $repos->div;
    $this->repoOrg = $repos->org;

    $this->repoProject = $repos->project;
    $this->repoSchTeam = $repos->schTeam;
    $this->repoPhyTeam = $repos->phyTeam;
  }
  // Needs to be a parameter array
  public function process($params)
  {
    // Need project info
    $pid = $params['project_id'];

    $row = $this->repoProject->getRowForId($pid);
    if (!$row) return;

    $this->projectId  = $pid;
    $this->projectRow = $row;

    parent::process($params);
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
  protected function savePhyTeam($data,$orgId,$teamKey)
  {
    $projectRow = $this->projectRow;
    $projectId  = $this->projectId;

    // Gather up the data
    $datax = array();
    $datax['eayso_id']  = (int)$data['teamId'];
    $datax['eayso_des'] = $data['teamDes'];

    $datax['name']     = $data['teamName'];
    $datax['colors']   = $data['teamColors'];

    $datax['unit_id']          = $orgId;
    $datax['reg_year_id']      = $projectRow['cal_year'] - 2000;
    $datax['season_type_id']   = $projectRow['season_type_id'];
    $datax['division_id']      = $this->divs[substr($teamKey,0,4)]['id'];
    $datax['division_seq_num'] = (int)substr($teamKey,4,2);

    // Have one already?
    $row = $this->repoPhyTeam->getForEaysoId($data['teamId']);
    if ($row)
    {
      $id = $row['phy_team_id'];
      $datax['phy_team_id'] = $id;
      $this->repoPhyTeam->update($datax);
      return $datax;
    }
    // New record
    $id = $this->repoPhyTeam->insert($datax);
    $datax['phy_team_id'] = $id;

    if (!$id) die("Failed inserting physical team");
    $this->repoProject->addOrg ($this->projectId,$orgId);
    $this->repoProject->addTeam($this->projectId,$id);

    $this->count->inserted++;

    // Cerad_Debug::dump($id);
    // Cerad_Debug::dump($datax);
    // die('Inserted team');
    
    return $datax;
  }
  protected function saveSchTeam($data)
  {
    $teamKey = $this->repoPhyTeam->generateKey($data);
    
    // Gather up the data
    $datax = array();
    $datax['phy_team_id']      = $data['phy_team_id'];
    $datax['unit_id']          = $data['unit_id'];
    $datax['reg_year_id']      = $data['reg_year_id'];
    $datax['season_type_id']   = $data['season_type_id'];
    $datax['schedule_type_id'] = 1;
    $datax['division_id']      = $data['division_id'];
    $datax['sortx']            = $data['division_seq_num'];
    $datax['desc_short']       = $teamKey;
    $datax['project_id']       = $this->projectId;

    // Have one already?
    $row = $this->repoSchTeam->getRowForProjectPhyTeam($this->projectId,$datax['phy_team_id']);
    if ($row)
    {
      $id = $row['sch_team_id'];
      $datax['sch_team_id'] = $id;
      $this->repoSchTeam->update($datax);
      return;
    }
    // New record
    $this->repoSchTeam->insert($datax);

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
    $teamKey = $this->getTeamKeyx($teamDes);

    // printf("Key %s\n",$teamKey); die(); return;
    if (!$teamKey) return;

    // Need a organization
    $orgId = $this->repoOrg->getIdForKey($data['org']);
    if (!$orgId) return;
    
    // And save
    $phyTeamData = $this->savePhyTeam($data,$orgId,$teamKey);
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
      $personId = $this->getPersonForName($orgId,$data[$person['fname']],$data[$person['lname']]);
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
    // Nope nope nope
    foreach($volsx as $volx)
    {
      if (!isset($vols[$volx['vol_type_id']]))
      {
        // $this->directPhyTeamPerson->delete($volx['phy_team_person_id']);
      }
    }
    return;
  }
  protected function getTeamKey($teamDes)
  {
    $desigs = array
    (
      'U19indoor'    => 'U19C01',
      'U16indoor'    => 'U16C01',
      'IN_U14C2_LOO' => 'U14C02',
      'IN_U14C4_ORT' => 'U14C04',
      'IN_U14C6_DEN' => 'U14C06',
      'IN_U14C5_WAL' => 'U14C05',
      'IN_U14C1_WOR' => 'U14C01',
      'IN_U14C3_MON' => 'U14C03',
      'IN_U12C1_MOR' => 'U12C01',
      'IN_U12C4_ROL' => 'U12C04',
      'IN_U12C5_OWE' => 'U12C05',
      'IN_U12C2_DEG' => 'U12C02',
      'IN_U12C3_NOA' => 'U12C03',
      'IN_U12C6_SWA' => 'U12C06',
      'IN_U12C8_TOM' => 'U12C08',
      'IN_U12C7_UND' => 'U12C07',
      'IN_U10C1_BIE' => 'U10C01',
      'IN_U10C2_RUS' => 'U10C02',
      'IN_U10C3_WIL' => 'U10C03',
      'IN_U10C4_PAU' => 'U10C04',
      'IN_U10C5_LAN' => 'U10C05',
      'IN_U10C6_SWA' => 'U10C06',
      'Ind U5/U6 T1' => 'U06C01',
      'Ind U5/U6 T2' => 'U06C02',
      'Ind U5/U6 T3' => 'U06C03',
      'Ind U5/U6 T4' => 'U06C04',
      'Ind U5/U6 T5' => 'U06C05',
      'Ind U5/U6 T6' => 'U06C06',
      'Ind U5/U6 T7' => 'U06C07',
      'Ind U5/U6 T8' => 'U06C08',
      'In_U7/U8_C3'  => 'U08C03',
      'In_U7/U8_C4'  => 'U08C04',
      'In_U7/U8_C1'  => 'U08C01',
      'In_U7/U8_C2'  => 'U08C02',
      'In_U7/U8_C5'  => 'U08C05',
      'In_U7/U8_C7'  => 'U08C07',
      'In_U7/U8_C8'  => 'U08C08',
      'In_U7/U8_C6'  => 'U08C06',
    );
    if (!isset($desigs[$teamDes])) die("Team Desig $teamDes");
    return $desigs[$teamDes];
  }
  protected function getTeamKeyx($teamDes)
  {

    $teamDes  = str_replace('FAY','',     $teamDes);
    $teamDes  = str_replace('SL', '',     $teamDes);
    $teamDes  = str_replace(' Team ', '', $teamDes);

    $teamDes  = str_replace('R160-','', $teamDes);
    $teamDes  = str_replace('160-', '', $teamDes);

    $teamDes  = str_replace('-','', $teamDes);
    $teamDes  = str_replace('_',' ',$teamDes);
    $teamDess = explode(' ',$teamDes);
    $teamDes  = $teamDess[0];

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

    'U6C'  => array('id' =>  3, 'age' =>  6, 'sex' => 'C'),
    'U6B'  => array('id' =>  1, 'age' =>  6, 'sex' => 'B'),
    'U6G'  => array('id' =>  2, 'age' =>  6, 'sex' => 'G'),
    'U6'   => array('id' =>  3, 'age' =>  6, 'sex' => 'C'),

    'U06C' => array('id' =>  3, 'age' =>  6, 'sex' => 'C'),
    'U06B' => array('id' =>  1, 'age' =>  6, 'sex' => 'B'),
    'U06G' => array('id' =>  2, 'age' =>  6, 'sex' => 'G'),

    'U7C'  => array('id' => 27, 'age' =>  7, 'sex' => 'C'),
    'U7B'  => array('id' => 25, 'age' =>  7, 'sex' => 'B'),
    'U7G'  => array('id' => 26, 'age' =>  7, 'sex' => 'G'),

    'U07C' => array('id' => 27, 'age' =>  7, 'sex' => 'C'),
    'U07B' => array('id' => 25, 'age' =>  7, 'sex' => 'B'),
    'U07G' => array('id' => 26, 'age' =>  7, 'sex' => 'G'),

    'U8C'  => array('id' =>  6, 'age' =>  8, 'sex' => 'C'),
    'U8B'  => array('id' =>  4, 'age' =>  8, 'sex' => 'B'),
    'U8G'  => array('id' =>  5, 'age' =>  8, 'sex' => 'G'),

    'U08C' => array('id' =>  6, 'age' =>  8, 'sex' => 'C'),
    'U08B' => array('id' =>  4, 'age' =>  8, 'sex' => 'B'),
    'U08G' => array('id' =>  5, 'age' =>  8, 'sex' => 'G'),

    'U10C' => array('id' =>  9, 'age' => 10, 'sex' => 'C'),
    'U10B' => array('id' =>  7, 'age' => 10, 'sex' => 'B'),
    'U10G' => array('id' =>  8, 'age' => 10, 'sex' => 'G'),

    'U12C' => array('id' => 12, 'age' => 12, 'sex' => 'C'),
    'U12B' => array('id' => 10, 'age' => 12, 'sex' => 'B'),
    'U12G' => array('id' => 11, 'age' => 12, 'sex' => 'G'),

    'U14C' => array('id' => 15, 'age' => 14, 'sex' => 'C'),
    'U14B' => array('id' => 13, 'age' => 14, 'sex' => 'B'),
    'U14G' => array('id' => 14, 'age' => 14, 'sex' => 'G'),

    'U16C' => array('id' => 18, 'age' => 16, 'sex' => 'C'),
    'U16B' => array('id' => 16, 'age' => 16, 'sex' => 'B'),
    'U16G' => array('id' => 17, 'age' => 16, 'sex' => 'G'),

    'U19C' => array('id' => 21, 'age' => 19, 'sex' => 'C'),
    'U19B' => array('id' => 19, 'age' => 19, 'sex' => 'B'),
    'U19G' => array('id' => 20, 'age' => 19, 'sex' => 'G'),

    'VIP'  => array('age' =>  0, 'sex' => 'C'),
  );
}
?>

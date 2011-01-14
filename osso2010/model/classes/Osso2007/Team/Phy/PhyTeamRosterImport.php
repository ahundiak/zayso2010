<?php
class Osso2007_Team_Phy_PhyTeamRosterImport extends Osso2007_Team_Phy_PhyTeamImport
{
  protected $readerClassName = 'Osso_Team_Phy_PhyTeamRosterReader';
  protected $teams   = array();

  protected $regTypeOsso = 101;
  protected $regTypeAyso = 102;

  protected function getPhyTeam($regionId,$teamDes)
  {
    $search = array(
      'unit_id'        => $regionId,
      'eayso_des'      => $teamDes,
      'reg_year_id'    => 11,
      'season_type_id' => 2,
    );
    $result = $this->directPhyTeam->fetchRow($search);
    return $result->row;
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

    // Need a organization
    $orgId = $this->repoOrg->getIdForKey($data['region']);
    if (!$orgId) return;

    // Physical team should always exist
    $phyTeamData = $this->getPhyTeam($orgId,$teamDes);
    if (!$phyTeamData) die('No existing physical team for ' . $teamDes);

    // Get volunteers based on aysoid
    $persons = array
    (
      array('type_id' => 16, 'aysoid' => 'headCoachAysoid'),
      array('type_id' => 17, 'aysoid' => 'asstCoachAysoid'),
      array('type_id' => 18, 'aysoid' => 'managerAysoid'),
    );
    $vols = array();
    foreach($persons as $person)
    {
      $personId = $this->getPersonForAysoid($orgId,$data[$person['aysoid']]);
      if ($personId) $vols[$person['type_id']] = $personId;
    }
    $this->insertPhyTeamPersons($phyTeamData,$vols);

    return;
  }
}
?>

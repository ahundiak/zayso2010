<?php
class Osso2007_Team_Coach_CoachContactReport
{
  protected $context;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}
  public function getOutputFileName() { return 'CoachContact.csv'; }

  protected function getCoachContactInfo($params)
  {
    $sql = <<<EOT
SELECT
  phy_team.phy_team_id      AS phy_team_id,
  phy_team.division_seq_num AS phy_team_seq_num,
  phy_team_org.keyx         AS phy_team_org_key,
  phy_team_div.desc_pick    AS phy_team_div_key,

  coach_head.fname          AS coach_head_fname,
  coach_head.nname          AS coach_head_nname,
  coach_head.lname          AS coach_head_lname,

  email.valuex              AS coach_head_email,
  phone_home.valuex         AS coach_head_phone_home,
  phone_work.valuex         AS coach_head_phone_work,
  phone_cell.valuex         AS coach_head_phone_cell

FROM osso2007.project_item AS project_team_item

LEFT JOIN osso2007.phy_team AS phy_team ON phy_team.phy_team_id = project_team_item.item_id
LEFT JOIN osso2007.unit     AS phy_team_org ON phy_team_org.unit_id     = phy_team.unit_id
LEFT JOIN osso2007.division AS phy_team_div ON phy_team_div.division_id = phy_team.division_id

LEFT JOIN osso2007.phy_team_person AS coach_headx ON
  coach_headx.phy_team_id = phy_team.phy_team_id AND
  coach_headx.vol_type_id = 16

LEFT JOIN osso2007.person AS coach_head ON coach_headx.person_id = coach_head.person_id

LEFT JOIN eayso.reg_prop AS email ON
  email.reg_num = coach_head.aysoid AND email.reg_type = 102 AND email.typex = 21

LEFT JOIN eayso.reg_prop AS phone_home ON
  phone_home.reg_num = coach_head.aysoid AND phone_home.reg_type = 102 AND phone_home.typex = 11

LEFT JOIN eayso.reg_prop AS phone_work ON
  phone_work.reg_num = coach_head.aysoid AND phone_work.reg_type = 102 AND phone_work.typex = 12

LEFT JOIN eayso.reg_prop AS phone_cell ON
  phone_cell.reg_num = coach_head.aysoid AND phone_cell.reg_type = 102 AND phone_cell.typex = 13

WHERE
  project_team_item.project_id IN (:project_id) AND
  project_team_item.type_id = 2

ORDER BY
  phy_team_org_key,
  phy_team_div_key,
  phy_team_seq_num
;
EOT;
    $rows = $this->context->db->fetchRows($sql,array('project_id' => 70));
    return $rows;

    Cerad_Debug::dump($rows[0]);
  }
  protected function formatPhone($phone)
  {
    if (!$phone) return null;
    return substr($phone,0,3) . '.' . substr($phone,3,3) . '.' . substr($phone,6);
  }
  public function process($params)
  {
    $lines = array();
    $lines[] = 'Region,Div,Team,First Name,Nick Name,Last Name,Email,Cell Phone,Home Phone,Work Phone';

    $coaches = $this->getCoachContactInfo($params);
    foreach($coaches AS $coach)
    {
      $team = sprintf('%s-%s-%02u %s',
        $coach['phy_team_org_key'],
        $coach['phy_team_div_key'],
        $coach['phy_team_seq_num'],
        $coach['coach_head_lname']);

      $line = array
      (
        $coach['phy_team_org_key'],
        $coach['phy_team_div_key'],
        $team,
        $coach['coach_head_fname'],
        $coach['coach_head_nname'],
        $coach['coach_head_lname'],
        $coach['coach_head_email'],
        $this->formatPhone($coach['coach_head_phone_cell']),
        $this->formatPhone($coach['coach_head_phone_home']),
        $this->formatPhone($coach['coach_head_phone_work']),
      );
      $lines[] = implode(',',$line);
    }
    $response = $this->context->response;
    $response->setBody(implode("\n",$lines));
    $response->setFileHeaders('CoachContact.csv');

    return true;
  }
}

?>

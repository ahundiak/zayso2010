<?php
class Osso2007_Referee_Points_RefPointsMonrovia extends Osso2007_Referee_Points_RefPointsBase
{
  public function getOutputFileName() { return 'RefPointsMonrovia.xml'; }
	
  public function linkTeamsRefereesx($teams,$referees)
  {
    $db = $this->context->db;
    	
    $sql = <<<EOT
SELECT * FROM phy_team_referee 

WHERE 
  phy_team_referee.unit_id        = {$this->unitId}       AND
  phy_team_referee.season_type_id = {$this->seasonTypeId} AND 
  phy_team_referee.reg_year_id    = {$this->regYearId}

ORDER BY referee_id, pri_tourn;

EOT;

    $rows = $db->fetchRows($sql);
    foreach($rows as $row)
    {
      $teamId    = $row['phy_team_id'];
      $refereeId = $row['referee_id'];
    		
      if (!isset($teams   [$teamId]   )) die("Missing team $teamId");

      if (!isset($referees[$refereeId]))
      {
        //echo("Missing referee $refereeId\n"); // From 2008
        
      }
      else
      {
        $team    = $teams[$teamId];
        $referee = $referees[$refereeId];
    		
        $team->addReferee($referee);
        $referee->addTeam($team,$row['pri_regular'],$row['max_regular']);
    }}
  }
  public function calcRefereePoints($referees,$events)
  {
    foreach($events as $event)
    {
      foreach($event->referees as $eventReferee)
      {
        $refereeId = $eventReferee->id;
        if (isset($referees[$refereeId]))
        {
          $referee = $referees[$refereeId];
          $referee->incGameCnt();
        //if ($refereeId == 1) printf("Incremented game count {$event->id}\n");
        }
      }
    }
  }
  public function process($params = array())
  {
    if (isset($params['unit_id'])) $this->unitId = $params['unit_id'];
    else                           $this->unitId = 1;
		
    $teams    = $this->getTeams();
    $referees = $this->getReferees();
    $events   = $this->getEventReferees();
		
    $this->calcRefereePoints($referees,$events);
		
    $this->linkTeamsReferees($teams,$referees);
		
    // Save data
    $this->teams    = $teams;
    $this->referees = $referees;
		           
    // Render
    ob_start();
    include 'Osso2007/Referee/Points/RefPointsMonroviaTpl.xml.php';
    $out = ob_get_clean();

    // Save
    $response = $this->context->response;
    $response->setBody($out);

    if (isset($params['xmlFileName'])) $xmlFileName = $params['xmlFileName'];
    else                               $xmlFileName = 'RefPointsMonrovia.xml';
    $response->setFileHeaders($xmlFileName);

    return true;
  }
}
?>
<?php
/* --------------------------------------------------------------
 * Huntsville Referee Points System
 */
class Osso2007_Referee_Points_RefPointsHuntsville extends Osso2007_Referee_Points_RefPointsBase
{
  public function getOutputFileName() { return 'RefPointsHuntsville.xml'; }

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

          $points = 0;

          // Get the points array
          switch($eventReferee->positionId)
          {
            case 10:          $divPoints = self::$divPointsCR;  break;
            case 11: case 12: $divPoints = self::$divPointsAR;  break;
            default:          $divPoints = array();
          }
          $divId = $event->divId;

          if (isset($divPoints[$divId]))
          {
            $points = $divPoints[$divId];
          }

          // Madison home games only
          // if ($event->unitHomeId != 4) $points = 0;

          // Apply the points
          if ($points)
          {
            if ($event->point2 == 1)  $referee->points->pending   += $points;
            if ($event->point2 == 3)  $referee->points->processed += $points;
          }
        }
      }
    }
  }

  protected function calcTeamPoints($teams,$referees)
  {
    foreach($referees as $referee)
    {
      foreach($referee->teams as $team)
      {
        $pointsMax  = $referee->getTeamMax($team);
        $pointsLeft = $referee->points->left;
        if ($pointsLeft)
        {
          if ($pointsLeft < $pointsMax)
          {
            $team->points->team += $pointsLeft;
            $referee->setTeamAct($team,$pointsLeft);
            $referee->points->left = 0;
          }
          else
          {
            $team->points->team    += $pointsMax;
            $referee->setTeamAct($team,$pointsMax);
            $referee->points->left -= $pointsMax;
          }
        }
      }
    }
  }
  public function process($params = array())
  {
    if (isset($params['unit_id'])) $this->unitId = $params['unit_id'];
    else                           $this->unitId = 7;

    $this->unitId = 7;
    
    $teams    = $this->getTeams();
    $referees = $this->getReferees();
    $events   = $this->getEventReferees();

    $this->calcRefereePoints($referees,$events);
    $this->linkTeamsReferees($teams,$referees);

    // Transfer points to teams
    $this->calcTeamPoints($teams,$referees);

    // Save data
    $this->teams    = $teams;
    $this->referees = $referees;

    // Render
    ob_start();
    include 'Osso2007/Referee/Points/RefPointsMadisonTpl.xml.php';
    $out = ob_get_clean();

    // Save
    $response = $this->context->response;
    $response->setBody($out);

    if (isset($params['xmlFileName'])) $xmlFileName = $params['xmlFileName'];
    else                               $xmlFileName = 'RefPointsHuntsville.xml';
    $response->setFileHeaders($xmlFileName);

    return true;
  }
    static $divPointsCR = array(
            4 => 0.25,  5 => 0.25,  6 => 0.25, // U08
            7 => 1.00,  8 => 1.00,  9 => 1.00, // U10
           10 => 1.00, 11 => 1.00, 12 => 1.00, // U12
           13 => 1.00, 14 => 1.00, 15 => 1.00, // U14
           16 => 1.00, 17 => 1.00, 18 => 1.00, // U16
           19 => 1.00, 20 => 1.00, 21 => 1.00, // U19
    );
    static $divPointsAR = array(
            4 => 0.0,  5 => 0.0,  6 => 0.0, // U08
            7 => 0.5,  8 => 0.5,  9 => 0.5, // U10
           10 => 0.5, 11 => 0.5, 12 => 0.5, // U12
           13 => 0.5, 14 => 0.5, 15 => 0.5, // U14
           16 => 0.5, 17 => 0.5, 18 => 0.5, // U16
           19 => 0.5, 20 => 0.5, 21 => 0.5, // U19
    );		
}
?>

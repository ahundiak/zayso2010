<?php
/* --------------------------------------------------------------
 * Madison Referee Points System
 *
 * Only Madison teams U10 and higher
 * Only Madison referees, certified in eayso, vol form 2009 or greater
 *
 * The 2009 version only counted Madison home events, 2010 does not currently do that
 */
class Osso2007_Referee_Points_RefPointsMadison extends Osso2007_Referee_Points_RefPointsBase
{
  public function getOutputFileName() { return 'RefPointsMadison.xml'; }

  public function calcRefereePoints($referees,$events)
  {
    // Points for each division
    $divPointsCR = array
    (
       4 => 1,  5 => 1,  6 => 1, // U08
       7 => 2,  8 => 2,  9 => 2, // U10
      10 => 2, 11 => 2, 12 => 2, // U12
      13 => 3, 14 => 3, 15 => 3, // U14
      16 => 4, 17 => 4, 18 => 4, // U16
      19 => 4, 20 => 4, 21 => 4, // U19
    );
    $divPointsAR = array
    (
       4 => 0,  5 => 0,  6 => 0, // U08
       7 => 1,  8 => 1,  9 => 1, // U10
      10 => 1, 11 => 1, 12 => 1, // U12
      13 => 1, 14 => 1, 15 => 1, // U14
      16 => 2, 17 => 2, 18 => 2, // U16
      19 => 2, 20 => 2, 21 => 2, // U19
    );
    $divPoints4th = array
    (
      16 => 1, 17 => 1, 18 => 1, // U16
      19 => 1, 20 => 1, 21 => 1, // U19
    );

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
            case 10:          $divPoints = $divPointsCR;  break;
            case 11: case 12: $divPoints = $divPointsAR;  break;
            case 13:          $divPoints = $divPoints4th; break;
            default:          $divPoints = array();
          }
          $divId = $event->divId;

          if (isset($divPoints[$divId]))
          {
            $points = $divPoints[$divId];
            if (($points) &&
              ($referee->isYouth) &&
              ($divId >= 7) &&
              ($eventReferee->positionId != 13)) $points++;
          }

          // Madison home games only
          if ($event->unitHomeId != 4) $points = 0;

          // Apply the points
          if ($points)
          {
            if ($event->point2 == 1)  $referee->points->pending   += $points;
            if ($event->point2 == 3)  $referee->points->processed += $points;

            $referee->points->left = $referee->points->processed;
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
    else                           $this->unitId = 4;

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
    else                               $xmlFileName = 'RefPointsMadison.xml';
    $response->setFileHeaders($xmlFileName);

    return true;
  }
}
?>

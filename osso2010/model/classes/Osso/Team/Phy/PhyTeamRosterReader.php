<?php
/* -------------------------------------------
 * Tournament Rosters
 * Region #	Region Name	Team Designation	Team Name
 *
 * Team Coach First Name	Team Coach Last Name	Team Coach AYSO ID
 * Team Coach Phone	Team Coach Cell Phone	Team Coach Email
 * Team Coach Certification/Training	CoachCertDate
 *
 * Asst. Team Coach First Name	Asst. Team Coach Last Name	Asst. Team Coach AYSO ID
 * Asst. Team Coach Phone	Asst. Team Coach Cell Phone	Asst. Team Coach Email
 * Asst. Team Coach Certification/Training	AsstCoachCertDate
 *
 * Team Parent First Name	Team Parent Last Name	Team Parent AYSOID
 * Team Parent Phone	Team Parent Cell Phone	Team Parent Email
 * Team Parent Certification/Training	ParentCoachCertDate
 *
 * Sponsor Name	Team Color	Player AYSO ID	JerseyNumber	Player First Name	Player Last Name	Player Street	Player City	Player State	Player Zip	Player Mailing Street	Player Mailing City	Player Mailing State	Player Mailing Zip	Player Home Phone	Player DOB	Player Age	DivisionName

 */
class Osso_Team_Phy_PhyTeamRosterReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'Team Designation'          => 'teamDes',
    'Region #'                  => 'region',
    'Team Coach AYSO ID'        => 'headCoachAysoid',
    'Asst. Team Coach AYSO ID'  => 'asstCoachAysoid',
    'Team Parent AYSOID'        => 'managerAysoid',
  );
}
?>

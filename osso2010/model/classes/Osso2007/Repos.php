<?php
class Osso2007_Repos extends Cerad_Services
{
  protected $itemClassNames = array
  (
    'project' => 'Osso2007_Project_ProjectRepo',
      
    'schTeam' => 'Osso2007_Team_Sch_SchTeamRepo',
    'phyTeam' => 'Osso2007_Team_Phy_PhyTeamRepo',

    'org'     => 'Osso2007_Org_OrgRepo',
    'div'     => 'Osso2007_Div_DivRepo',
    'misc'    => 'Osso2007_Misc_MiscRepo',
    'site'    => 'Osso2007_Site_SiteRepo',
      
    'event'      => 'Osso2007_Event_EventRepo',
    'eventClass' => 'Osso2007_Event_Class_EventClassRepo',

    'schedule'   => 'Osso2007_Schedule_SchRepo',
  );
}
?>

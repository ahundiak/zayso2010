<?php

namespace Zayso\AreaBundle\Component\Import;

use Zayso\CoreBundle\Component\Import\BaseImport;
use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\Team;

class TeamPhysicalEaysoSummaryImport extends BaseImport
{
    // RegionNumber	TeamDesignation	TeamKey	TeamName DivisionName	TeamColors	TeamID	
    // TeamCoachFName	TeamCoachLName	TeamCoachPhone	TeamCoachEmail

    protected $record = array
    (
      'projectId'    => array('cols' => 'PID',             'req' => false, 'default' => 0),
      'regionNumber' => array('cols' => 'RegionNumber',    'req' => true,  'default' => 0),
      'teamDesig'    => array('cols' => 'TeamDesignation', 'req' => true,  'default' => ''),
      'teamKey'      => array('cols' => 'TeamKey',         'req' => false, 'default' => null),
      'teamId'       => array('cols' => 'TeamID',          'req' => true,  'default' => 0),
      'teamName'     => array('cols' => 'TeamName',        'req' => true,  'default' => ''),
      'teamColors'   => array('cols' => 'TeamColors',      'req' => true,  'default' => ''),
      'divName'      => array('cols' => 'DivisionName',    'req' => true,  'default' => ''),
        
      'hcFirstName'  => array('cols' => 'TeamCoachFName',  'req' => false, 'default' => ''),
      'hcLastName'   => array('cols' => 'TeamCoachLName',  'req' => false, 'default' => ''),
      'hcPhone'      => array('cols' => 'TeamCoachPhone',  'req' => false, 'default' => ''),
      'hcEmail'      => array('cols' => 'TeamCoachEmail',  'req' => false, 'default' => ''),
    );
    public function __construct($teamManager)
    {
        parent::__construct($teamManager->getEntityManager());
        $this->teamManager = $teamManager;
    }
    public function processItem($item)
    {
        if (!$item->teamDesig) return;
        if (!$item->teamId)    return;
        
        $this->total++;
        
        // Project id 
        if ($item->projectId) $projectId = $item->projectId;
        else                  $projectId = $this->projectId;
        
        // Check for updates or new
        $teamManager = $this->teamManager;
        $em = $this->getEntityManager();
        
        $team = $teamManager->loadTeamForEaysoId($projectId,$item->teamId);
        if (!$team)
        {
            // Must have team key to create
            if (!$item->teamKey) return;
            
            $team = new Team();
            $team->setType  (Team::TypePhysical);
            $team->setSource(Team::SourceEayso);
            
            $team->setProject($teamManager->getProjectReference($projectId));
            $team->setEaysoTeamId($item->teamId);
            $em->persist($team);
            
        }
        // Build unique designation
        $desig = sprintf('R%04u %s',$item->regionNumber,$item->teamDesig);
        $team->setEaysoTeamDesig($desig);
        
        // Always need a team key
        if ($item->teamKey) $teamKey = $item->teamKey;
        else                $teamKey = $team->getTeamKey();
        
        $team->setTeamKey($teamKey);
        
        // Rebuild expanded in case coaches lat name changed
        $expanded = sprintf('%s-%s-%s',substr($teamKey,0,5),substr($teamKey,5,4),substr($teamKey,9));
        if ($item->hcLastName) $expanded .= ' ' . $item->hcLastName;
        $team->setTeamKeyExpanded($expanded);
        
        // Pull age/gender from team key
        $team->setAge   (substr($teamKey,5,3));
        $team->setGender(substr($teamKey,8,1));
        
        // Assume region exists
        $regionId = sprintf('AYSOR%04u',$item->regionNumber);
        $region = $teamManager->getRegionReference($regionId);
        $team->setOrg($region);
        
        // Some misc
        $team->setTeamName  ($item->teamName);
        $team->setTeamColors($item->teamColors);        
    }
}
?>

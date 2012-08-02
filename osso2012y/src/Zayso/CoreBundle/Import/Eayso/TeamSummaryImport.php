<?php
namespace Zayso\CoreBundle\Import\Eayso;

use Zayso\CoreBundle\Import\BaseImport;

class TeamSummaryImport extends BaseImport
{
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
    protected function processItem($item)
    {
        $this->total++;
        echo sprintf("Item %s %s\n",$item->teamDesig,$item->hcLastName);
    }

}
?>

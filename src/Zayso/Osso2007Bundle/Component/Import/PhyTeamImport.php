<?php
/* ========================================================
 * This is actually more of an update hack
 */
namespace Zayso\Osso2007Bundle\Component\Import;

use Zayso\Osso2007Bundle\Entity\Project;
use Zayso\Osso2007Bundle\Entity\PhyTeam;
use Zayso\Osso2007Bundle\Entity\PhyTeamPlayer;

use Zayso\Osso2007Bundle\Component\Import\BaseImport;
use Zayso\ZaysoBundle\Component\Debug;

class PhyTeamImport extends BaseImport
{
    protected $record = array
    (
      'region'             => array('cols' => 'RegionNumber',    'req' => true,  'default' => 0),
      'eaysoDesig'         => array('cols' => 'TeamDesignation', 'req' => true,  'default' => ''),
      'eaysoId'            => array('cols' => 'TeamID',          'req' => true,  'default' => 0),
      'name'               => array('cols' => 'TeamName',        'req' => false, 'default' => ''),
      'colors'             => array('cols' => 'TeamColors',      'req' => false, 'default' => ''),
      'age'                => array('cols' => 'DivisionName',    'req' => false, 'default' => ''),
    );

    protected $teamManager;
    protected function getTeamManager() { return $this->teamManager; }
    
    public function __construct($teamManager)
    {
        $this->teamManager = $teamManager;
        parent::__construct($teamManager->getEntityManager());

    }
    public function processItem($item)
    {
        if (!$item->eaysoDesig) return;
        if (!$item->eaysoId)    return;

        $this->total++;

        $eaysoId = $item->eaysoId;
        $phyTeam = $this->getTeamManager()->getPhyTeamForEaysoId($this->projectId,$eaysoId);
        if (!$phyTeam)
        {
            echo "Not found: $eaysoId\n";
            return;
        }
        $phyTeam->setName    ($item->name);
        $phyTeam->setColors  ($item->colors);
        $phyTeam->setEaysoDes($item->eaysoDesig);

        // Flush em all at once
        return;
   }
}
?>
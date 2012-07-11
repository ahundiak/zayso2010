<?php
namespace Zayso\Osso2007Bundle\Component\Import;

use Zayso\Osso2007Bundle\Entity\Project;
use Zayso\Osso2007Bundle\Entity\PhyTeam;
use Zayso\Osso2007Bundle\Entity\PhyTeamPlayer;

use Zayso\Osso2007Bundle\Component\Import\BaseImport;
use Zayso\ZaysoBundle\Component\Debug;

class PlayerImport extends BaseImport
{
    protected $record = array
    (
      'region'             => array('cols' => 'Region #',         'req' => true,  'default' => 0),
      'eaysoDesig'         => array('cols' => 'Team Designation', 'req' => true,  'default' => ''),
      'name'               => array('cols' => 'Team Name',        'req' => false, 'default' => ''),
      'colors'             => array('cols' => 'Team Color',       'req' => false, 'default' => ''),
      'age'                => array('cols' => 'DivisionName',     'req' => false, 'default' => ''),
      
      'playerFirstName'    => array('cols' => 'Player First Name', 'req' => false, 'default' => ''),
      'playerLastName'     => array('cols' => 'Player Last Name',  'req' => false, 'default' => ''),
      'playerJerseyNumber' => array('cols' => 'JerseyNumber',      'req' => false, 'default' => ''),
      'playerAysoid'       => array('cols' => 'Player AYSO ID',    'req' => false, 'default' => ''),

      'headCoachFirstName' => array('cols' => 'Team Coach First Name', 'req' => false, 'default' => ''),
      'headCoachLastName'  => array('cols' => 'Team Coach Last Name',  'req' => false, 'default' => ''),
      'headCoachEmail'     => array('cols' => 'Team Coach Email',      'req' => false, 'default' => ''),
      'headCoachPhone'     => array('cols' => 'Team Coach Cell Phone', 'req' => false, 'default' => ''),
      'headCoachAysoid'    => array('cols' => 'Team Coach AYSO ID',    'req' => false, 'default' => ''),

    );
// Region #,Region Name,Team Designation,Team Name,
//
// Team Coach First Name,Team Coach Last Name,Team Coach AYSO ID,Team Coach Phone,Team Coach Cell Phone,Team Coach Email,
// Team Coach Certification/Training,CoachCertDate,
// Asst. Team Coach First Name,Asst. Team Coach Last Name,Asst. Team Coach AYSO ID,Asst. Team Coach Phone,Asst. Team Coach Cell Phone,
// Asst. Team Coach Email,Asst. Team Coach Certification/Training,AsstCoachCertDate,
// Team Parent First Name,Team Parent Last Name,Team Parent AYSOID,Team Parent Phone,Team Parent Cell Phone,Team Parent Email,
// Team Parent Certification/Training,ParentCoachCertDate,Sponsor Name,
//
// Team Color,Player AYSO ID,JerseyNumber,Player First Name,Player Last Name,
//
// Player Street,Player City,Player State,Player Zip,Player Mailing Street,Player Mailing City,Player Mailing State,
// Player Mailing Zip,Player Home Phone,Player DOB,Player Age,DivisionName

    protected $teamMnagaer;
    protected $phyTeam = null;

    protected function getTeamManager() { return $this->teamManager; }
    
    public function __construct($teamManager)
    {
        $this->teamManager = $teamManager;
        parent::__construct($teamManager->getEntityManager());

    }
    public function processItem($item)
    {
        if (!$item->region)       return;
        if (!$item->eaysoDesig)   return;
        if (!$item->playerAysoid) return;

        $this->total++;

        $eaysoDesig = $item->eaysoDesig;
        
        // Little cache majic
        if ($this->phyTeam)
        {
            if ($this->phyTeam->getEaysoDes() != $eaysoDesig)
            {
                $this->getEntityManager()->flush();
                $this->phyTeam = null;
            }
        }
        if (!$this->phyTeam)
        {
            $phyTeam = $this->getTeamManager()->getPhyTeamForEaysoDes($this->projectId,$item->region,$eaysoDesig);
            if (!$phyTeam)
            {
                echo "Not found: '$eaysoDesig'\n";
                return;
            }
            $this->phyTeam = $phyTeam;
            $phyTeam->getPlayers();
        }
        $phyTeam = $this->phyTeam;
        
        // See if already on it
        $player = $phyTeam->getPlayer($item->playerAysoid);
        if (!$player)
        {
            $player = new PhyTeamPlayer();
            $player->setPhyTeam($phyTeam);
            $player->setAysoid($item->playerAysoid);
            $this->getEntityManager()->persist($player);
        }
        $player->setFirstName($item->playerFirstName);
        $player->setLastName ($item->playerLastName);
        $player->setJersey   ($item->playerJerseyNumber);

        return;
   }
}
?>
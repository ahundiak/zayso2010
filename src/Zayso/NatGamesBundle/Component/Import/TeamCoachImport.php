<?php

namespace Zayso\NatGamesBundle\Component\Import;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event        as Game;
use Zayso\CoreBundle\Entity\EventTeam    as GameTeamRel;
use Zayso\CoreBundle\Entity\EventPerson  as GameReferee;
use Zayso\CoreBundle\Entity\ProjectField as Field;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class TeamImport extends ExcelBaseImport
{
    protected $manager = null;
    protected $orgs  = null;
    protected $teams = null;
    
    public function __construct($manager,$projectId = 0)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
        parent::__construct($manager->getEntityManager());
    }
    protected function processPhyTeamRow($row)
    {
        $region  = (int)trim($row[13]);
        $age     =      trim($row[14]);
        $gender  =      trim($row[15]);
        $coach   =      trim($row[20]);
        
        $div = $age . $gender;
        
        if (!$region) return;
        
        $parts = explode(' ', $coach);
        $coach = $parts[count($parts) - 1];
        
        // R0003-U16BR
        $teamKey = sprintf('R%04u-%sR',$region,$div);
        
        $team = $this->manager->loadPhyTeamForKey($this->projectId,$teamKey);
        if (!$team)
        {
            echo 'No phy team for ' . $div . ' ' . $region . ' ' . $coach . "\n";
            return;
        }
        
        $orgKey = sprintf('AYSOR%04u',$region);
        $org = $this->manager->loadOrgForKey($orgKey);
        if (!$org)
        {
            echo 'No org ' . $region . ' ' . $coach . "\n";
            return;
        }
        $teamDesc = sprintf('R%04u-%s %s',$region,$div,$coach);
        $team->setDesc($teamDesc);
        
      //echo $team->getKey() . ' ' . $region . ' ' . $coach . ' ' . $phyTeamDesc . "\n";
        return;
       
    }
    /* =================================================================
     * One sheet at a time
     */
    protected function processSheet($reader,$sheetName)
    {
        $sheet = $reader->getSheetByName($sheetName);
        if (!$sheet) return;
        $rows = $sheet->toArray();
        
        // Loop once for team
        foreach($rows as $row)
        {
            $this->processPhyTeamRow($row);
        }
    }
    protected function processGameTeams()
    {
        $gameTeams = $this->manager->loadGameTeamsForProject($this->projectId);
        foreach($gameTeams as $gameTeam)
        {
            $phyTeam = $gameTeam->getParent();
            if ($phyTeam)
            {
                $gameTeamDesc = $gameTeam->getDesc();
                $phyTeamDesc  = $phyTeam->getDesc();
                
                $coach = substr($phyTeamDesc,11);
                $desc = substr($gameTeamDesc,0,13) . ' ' . $coach;
                
                $gameTeam->setDesc($desc);
                
              //die($gameTeamDesc . ' # ' . $phyTeamDesc . ' # ' . $desc);
            }
        }
    }
    /* =================================================================
     * Merge this back into the base class after some testing
     */
    public function process($params = array())
    {
        
        // For tracking changes
        $this->getEntityManager()->getEventManager()->addEventListener(
            array(Events::postUpdate, Events::postRemove, Events::postPersist),
            $this);

        // Often have a project
        $projectId = $this->projectId;
        
        if ($projectId)
        {
            $this->projectId = $projectId;
            $this->project = $this->getEntityManager()->getReference('ZaysoCoreBundle:Project',$projectId);
        }

        // Need an input file
        if (isset($params['inputFileName'])) $inputFileName = $params['inputFileName'];
        else
        {
            $this->errors[] = 'No inputFileName';
            return $this->getResults();
        }
        $this->inputFileName = $inputFileName;

        // Client file name for web processing
        if (isset($params['clientFileName'])) $this->clientFileName = $params['clientFileName'];
        else                                  $this->clientFileName = $this->inputFileName;
        
        // Type of team
        if (isset($params['type'])) $this->teamType = $params['type'];
        else                        $this->teamType = 'regular';
        
        $reader = $this->excel->load($inputFileName);

        $sheets = array('U10G','U10B','U12B','U12G','U14B','U14G','U16B','U16G','U19G','U19B');
        foreach($sheets as $sheet)
        {
            $this->processSheet($reader,$sheet);
        }
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();

        $this->processGameTeams();
        
        // Finish up
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear(); // Need for multiple files

        return $this->getResults();
    }
 }
?>

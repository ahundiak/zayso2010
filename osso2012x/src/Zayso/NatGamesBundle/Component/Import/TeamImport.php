<?php

namespace Zayso\NatGamesBundle\Component\Import;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

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
    protected $orgs = null;
    
    public function __construct($manager,$projectId = 0)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
        parent::__construct($manager->getEntityManager());
    }
    protected function newTeam($div,$type = null, $key = null, $org = null)
    {
        $team = new Team();
        $team->setProject($this->project);
        $team->setType($type);
        $team->setStatus ('Active');
        $team->setSource ('import');
        $team->setKey    ($key);
        $team->setOrg    ($org);
        $team->setAge    (substr($div,0,3));
        $team->setGender (substr($div,3,1));
       
        return $team;
    }
    /* =============================================================
     * See if have a region
     */
    protected function processOrg($section,$area,$region)
    {
        $key = sprintf('AYSOR%04u',$region);
        if (isset($this->orgs[$key])) return $this->orgs[$key];
        
        $org = $this->manager->loadOrgForKey($key);
        if ($org)
        {
            // Maybe check section and area?
            
            // Got it
            $this->orgs[$key] = $org;
            return $org;
        }
        $this->orgs[$key] = true;
        
        echo sprintf("A%02u%s-R%04u\n",$section,$area,$region);
        return null;
    }
    /* =============================================================
     * Individual team row
     */
    protected function processTeamRow($row)
    {
        $section = (int)trim($row[0]);
        $area    =      trim($row[1]);
        $region  = (int)trim($row[2]);
        $div     =      trim($row[3]);
        $note    =      trim($row[4]);
        
        if ($note == 'open') return;
        
        $age    = substr($div,1,3);
        $gender = substr($div,0,1);
        
        $key = sprintf('R%04u-%s%s',$region,$age,$gender);
        
        //echo $key . "\n";
       
        $this->processOrg($section,$area,$region);
        
        //$this->processPhyTeam ($phyTeamKey);
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
            $this->processTeamRow($row);
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
        
        $this->orgs = array();
        
        $reader = $this->excel->load($inputFileName);

        $sheets = array('All Divisions Accepted');
        foreach($sheets as $sheet)
        {
            $this->processSheet($reader,$sheet);
        }

        // Finish up
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear(); // Need for multiple files

        return $this->getResults();
    }
 }
?>

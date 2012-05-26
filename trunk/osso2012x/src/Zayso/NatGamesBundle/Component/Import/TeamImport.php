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
     * Need when adding a new region
     */
    protected function processSection($section)
    {
        $key = sprintf('AYSOS%02u',$section);
        if (isset($this->orgs[$key])) return $this->orgs[$key];
        
        $org = $this->manager->loadOrgForKey($key);
        if ($org)
        {
           // Got it
            $this->orgs[$key] = $org;
            return $org;
        }
        
        // Make one
        echo $key . "\n";die();
        
    }
    /* =============================================================
     * Need when adding a new region
     */
    protected function processArea($section,$area)
    {
        $key = sprintf('AYSOA%02u%s',$section,$area);
        if (isset($this->orgs[$key])) return $this->orgs[$key];
        
        $org = $this->manager->loadOrgForKey($key);
        if ($org)
        {
           // Got it
            $this->orgs[$key] = $org;
            return $org;
        }
        
        // Make one
        echo $key . "\n";
        
        $sectionEntity = $this->processSection($section);
        $desc = sprintf('AYSO Area %02u%s',$section,$area);
        $org = new Org();
        $org->setId($key);
        $org->setParent($sectionEntity);
        $org->setDesc1($desc);
        $org->setStatus('Active');
        
        $this->manager->persist($org);
        
        $this->orgs[$key] = $org;
        return $org;
        
    }
    /* =============================================================
     * See if have a region
     */
    protected function processRegion($section,$area,$region)
    {
        $key = sprintf('AYSOR%04u',$region);
        if (isset($this->orgs[$key])) return $this->orgs[$key];
        
        $org = $this->manager->loadOrgForKey($key);
        if ($org)
        {
            // Maybe check section and area?
            $desc = $org->getDesc2();
            $sectionx = (int)substr($desc,1,2);
            $areax    =      substr($desc,3,1);
            if ($section != $sectionx)
            {
                echo sprintf("Mismatch section %s %d %s\n",$desc,$section,$area);
            }
            if ($area != $areax)
            {
                echo sprintf("Mismatch area    %s %d %s\n",$desc,$section,$area);
            }
            // Got it
            $this->orgs[$key] = $org;
            return $org;
        }
        $areaEntity = $this->processArea($section,$area);
        
        $desc1 = sprintf('AYSO Region %04u',$region);
        $desc2 = sprintf('A%02u%s-R%04u TBD, %s',$section,$area,$region,$areaEntity->getState());

        echo $desc2 . "\n";
        
        $org = new Org();
        $org->setId($key);
        $org->setParent($areaEntity);
        $org->setState ($areaEntity->getState());
        
        $org->setDesc1($desc1);
        $org->setDesc2($desc2);
        
        $org->setStatus('Active');
        
        $this->manager->persist($org);
        
        $this->orgs[$key] = $org;
        
        return $org;
        
        $this->orgs[$key] = true;
        
      //echo sprintf("A%02u%s-R%04u\n",$section,$area,$region);
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
        $org = $this->processRegion($section,$area,$region);
        
        $age    = substr($div,1,3);
        $gender = substr($div,0,1);
        
        $key = sprintf('R%04u-%s%s',$region,$age,$gender);
        
        //echo $key . "\n";
       
        // Should not have dups
        if (isset($this->teams[$key]))
        {
            echo "Dup Team Key $key \n";
            return;
        }
        // See if exists
        $team = $this->manager->loadPhyTeamForKey($this->projectId,$key);
        if ($team)
        {
            $this->teams[$key] = $team;
            return;
        }
        // Create
        $team = $this->newTeam($age . $gender,'physical', $key, $org);
        
        $this->manager->persist($team);
        
        $this->teams[$key] = $team;
        
        echo 'Added ' . $key . "\n";
       
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
        
        $this->orgs  = array();
        $this->teams = array();
        
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

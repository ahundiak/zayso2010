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

class AssignByNameImport extends ExcelBaseImport
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
    protected function loadReferees()
    {
        
    }
    protected function processGameRow($row)
    {
        $gameNum   = (int)trim($row[ 0]);
        $pos       =      trim($row[ 8]);
        $aysoid    =      trim($row[12]);
                
        if (!$gameNum) return;
        
        $game = $this->manager->loadEventForNum(52,$gameNum);
        if (!$game) return;
        
        $gamePerson = $game->getPersonForType($pos);
        if (!$gamePerson) return;
        
        $person = $this->manager->loadPersonForAysoid('AYSOV' . $aysoid);
        if (!$person)
        {
            if (!$gamePerson->getPerson()) return;
        }
        $gamePerson->setPerson($person);
        
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
            $this->processGameRow($row);
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
        $sheets = array('Games');
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

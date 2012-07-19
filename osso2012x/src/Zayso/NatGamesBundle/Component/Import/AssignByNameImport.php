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
    protected $persons = array();
    
    public function __construct($manager,$projectId = 0)
    {
        $this->manager   = $manager;
        $this->projectId = $projectId;
        parent::__construct($manager->getEntityManager());
    }
    protected function addReferee($person)
    {
        $name = $person->getPersonName();
        if (!$name)
        {
            echo sprintf("*** No name for %d\n",$person->getId());
            return;
        }
        if (!isset($this->persons[$name]))
        {
            $this->persons[$name] = $person;
            return;
        }
        echo sprintf("*** Dup name for %s %d %d\n",$name,$this->persons[$name],$id);
    }
    protected function loadReferees()
    {
        $persons = $this->manager->loadPersonsForProject($this->projectId);
        foreach($persons as $person)
        {
            $this->addReferee($person);
        }
        //die(sprintf("Loaded %d possible referees\n",count($this->persons)));
    }
    protected $names = array
    (
        'Lisa Kirk'         => '*Lisa Kirk',
        'Bob Mauch'         => '*Bob Mauch',
        'Rick Russell'      => '*Rick Russell',
        'Wayne Riley'       => '*Wayne Riley',
        'David Riley'       => '*David Riley',
        'Thierry Simon'     => '*Thierry Simon',
        'Thomas Eaken'      => '*Thomas Eaken',
        'Bob Mach'          => '*Bob Mach',
        'Byron Melger'      => '*Byron Melger',
        'Tom'               => '*Tom',
        'Amy Lee'           => '*Amy Lee',
          
        'Reuben Marques'    => 'Ruben Marques',
        'Don Coaswell'      => 'Dan Cogswell',
        'Steve Monookian'   => 'Steve Manookian',
        'Katy Jones'        => 'Katie Jones',
        'Kathy Jones'       => 'Katie Jones',
        'John Davis'        => 'Jon Davis',
        'Debbie Dakouzlion' => 'Debbie Dakouzlian',

        'Thomas Baker'      => 'tomb Baker',
        'Tom Baker'         => 'tomb Baker',
        'Nick Tagliorani'   => 'Nick Tagliareni',
        'Teresa Knudsen'    => 'Teresa Knudson',
        
        'Ryan Lauradon'     => 'Ryan Laudato',
        'Mike Orillian'     => 'Mike Orillion',
        'Eric Fettrolf'     => 'Eric Fetterolf',
        'Joe Breslin'       => 'joe breslin',
        'Andrei Apostoaei'  => 'Andrei Iulian Apostoaei',
        
        'Dave Livesay'      => 'Davey Livesay',
        'Loren Demoree'     => 'Loren Demaree',
        'Eric Ammons'       => 'Erica Ammons',
        'Jennifer Washburn' => 'J.R. Washburn',

        'Branden Bennett'   => 'B Bennett',
        'Jeff Levine'       => 'Jeffrey Levine',
        'Kevin Wayland'     => 'Keith Wayland',
        'Sam McCpnnell'     => 'Sam McConnell',
        'James Ripper'      => 'Jim Ripper',
        'Brad Berryman'     => 'Brad B Berryman',
        'Terence Hayden'    => 'Terry Hayden',
        
        'Daniel Leskinen'   => "Dan'l Leskinen",
        'Moises Ramirez'    => 'Coach Mo Ramirez',
        'Tim McConnell'     => 'Tim Mcconnell',
        'Terry James'       => 'Terry Jones',
        'John Waddelle'     => 'John Waddell',
        'Tom Gingee'        => "Tom Gintjee",
        
        '' => '',
        '' => '',
      
   );
   protected function assignReferee($game,$pos,$name)
    {   
        switch($name)
        {
            case '':
            case 'CR1':
            case 'AR1':
            case 'AR2':
            case 'Referee 3':
            case 'Cancelled':
                
                return;
        }
        if (isset($this->names[$name])) $name = $this->names[$name];
        if ($name[0] == '*') return;
        
        $gamePerson = $game->getPersonForType($pos);
        
        if (!$gamePerson) 
        {
            echo sprintf("No pos\n");
            return;
        }
        if (!isset($this->persons[$name]))
        {
            echo sprintf("No person for %s\n",$name);
            return;
        }
        $person = $this->persons[$name];
        
        $personx = $gamePerson->getPerson();
        if ($personx)
        {
            if ($personx->getPersonName() == $name) return;
        }
        
        $gamePerson->setPerson($person);
        
    }
    protected function processGameRow($row)
    {   
        $gameNum   = (int)trim($row[ 0]);
                
        if (!$gameNum) return;
        
        $game = $this->manager->loadEventForNum($this->projectId,$gameNum);
        if (!$game) return;
        
        $this->assignReferee($game,'CR',  trim($row[ 9]));
        $this->assignReferee($game,'AR 1',trim($row[10]));
        $this->assignReferee($game,'AR 2',trim($row[11]));
        
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
        if (isset($params['projectId'])) $projectId = $params['projectId'];
        else                             $projectId = $this->projectId;
        
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
        
        // Load list of officials
        $this->loadReferees();
        
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

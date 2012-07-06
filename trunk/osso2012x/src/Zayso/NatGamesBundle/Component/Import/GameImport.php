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

class GameImport extends ExcelBaseImport
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
    protected function processGameRow($row)
    {
        $gameId   = (int)trim($row[1]);
        $fieldId  = (int)trim($row[6]);
        
        $pool     =      trim($row[8]);
        
        $time     = (int)trim($row[5]);
        
        if (!$gameId) return;
        
        $game = $this->manager->loadEventForId($gameId);
        
        // $game->setPool($pool);
        $time = sprintf('%04u',$time);
        $game->setTime($time);
        
        return;
       
    }
    protected $teamsU10B = array
    (
        '1829' => 'A1 A01C-R0002 Yen',
        '1830' => 'A2 A05B-R0124 Hendrickson',
        '1831' => 'A3 A05G-R0727 Brown',
        '1833' => 'A4 A14I-R0644 Freedland',
        '1834' => 'A5 A07O-R0178 Bright',
        '1835' => 'A6 A12E-R0368 Linggi',
        '1841' => 'B1 A05C-R0160 Meehan',
        '1842' => 'B2 A05B-R0275 Duke',
        '1843' => 'B3 A01B-R0779 Giannetti',
        '1844' => 'B4 A14I-R0864 Hendrick',
        '1845' => 'B5 A07E-R0769 Castro',
        '1846' => 'B6 A13G-R1475 Gibson',
        '1836' => 'C1 A05C-R0498 Ramirez',
        '1837' => 'C2 A05B-R1159 Huffman',
        '1838' => 'C3 A14I-R1521 Shipe',
        '1839' => 'C4 A06D-R0418 Tepper',
        '1840' => 'C5 A09G-R0354 Service',
        '1847' => 'D1 A05B-R1390 Morgan',
        '1848' => 'D2 A05B-R0337 Ownby',
        '1849' => 'D3 A06F-R0399 Lundgren',
        '1850' => 'D4 A08H-R0881 Vandekerkhof',
        
        '1854' => 'E1 A01C-R0002 Yen',
        '1856' => 'E2 A01B-R0779 Giannetti',
        '1855' => 'E3 A06D-R0418 Tepper',
        '1852' => 'E4 A06F-R0399 Lundgren',
        '1866' => 'F1 A07O-R0178 Bright',
        '1867' => 'F2 A07E-R0769 Castro',
        '1868' => 'F3 A09G-R0354 Service',
        '1864' => 'F4 A05B-R0337 Ownby',
        '1860' => 'G1 A14I-R0644 Freedland',
        '1861' => 'G2 A05C-R0160 Meehan',
        '1862' => 'G3 A05C-R0498 Ramirez',
        '1858' => 'G4 A08H-R0881 Vandekerkhof',
        '1857' => 'G6 U10B',
        '1869' => 'H1 A12E-R0368 Linggi',
        '1872' => 'H2 A05B-R0275 Duke',
        '1873' => 'H3 A14I-R1521 Shipe',
        '1870' => 'H4 A05B-R1390 Morgan',
        '1871' => 'H5 U10B',
        '1874' => 'H6 U10B',
        '1853' => 'I1 A05B-R0124 Hendrickson',
        '1851' => 'I2 A05G-R0727 Brown',
        '1865' => 'I3 A14I-R0864 Hendrick',
        '1863' => 'I4 A13G-R1475 Gibson',
        '1859' => 'I5 A05B-R1159 Huffman',
    );
    protected function processPoolTeam($teamKey,$teamRel)
    {
        $team = $teamRel->getTeam();

        if (!$teamKey) $teamKey = 'H6 U10B';
        
        $teamId = null;
        foreach($this->teamsU10B as $teamxId => $teamxKey)
        {
            if ($teamxKey == $teamKey) $teamId = $teamxId;
        }
        if (!$teamId) die('*** Missing key ' . $teamKey);
        
        $teamRel->setTeam($this->manager->getTeamReference($teamId));
        
        echo $teamId . ' ' . $teamKey . "\n";
    }
    protected function processGameRowU10B($row)
    {
        $num  = (int)trim($row[0]);
 
        $pool =      trim($row[6]);
        
        $homeTeamKey  = trim($row[ 8]);
        $awayTeamKey  = trim($row[10]);

        if (!$num) return;
       
        $game = $this->manager->loadEventForNum(52,$num);
        if (!$game) return;
        
        $this->processPoolTeam($homeTeamKey,$game->getHomeTeam());
        $this->processPoolTeam($awayTeamKey,$game->getAwayTeam());
        
        $game->setPool($pool);
        
        
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
            $this->processGameRowU10B($row);
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

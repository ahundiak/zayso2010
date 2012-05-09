<?php
namespace Zayso\CoreBundle\Component\Import;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Format\Excel;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;


class ExcelBaseImport extends BaseImport
{
    protected $excel = null;
        
    public function __construct($em)
    {
        parent::__construct($em);
        
        $this->excel = new Excel();
    }
    protected function processInputFile($fileName, $sheetName = null)
    {
        $reader = $this->excel->load($fileName);
        
        if ($sheetName) $ws = $reader->getSheetByName($sheetName);
        else            $ws = $reader->getSheet(0);
        
        $rows = $ws->toArray();
        
         // Process the header
        $this->processHeaderRow(array_shift($rows));
        if (count($this->errors)) return;

        // Process the data
        foreach($rows as $row)
        {
            $item = $this->processDataRow($row);
            $this->processItem($item);
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
        if (isset($params['projectId']) && $params['projectId']) $projectId = $params['projectId'];
        else                                                     $projectId = 0;

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

        if (isset($params['sheetName'])) $sheetName = $params['sheetName'];
        else                             $sheetName = null;
        
        // Process it
        $this->processInputFile($inputFileName,$sheetName);

        // Finish up
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear(); // Need for multiple files

        return $this->getResults();
    }

}
?>

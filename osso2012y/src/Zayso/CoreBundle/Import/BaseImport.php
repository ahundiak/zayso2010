<?php
namespace Zayso\CoreBundle\Import;

use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;

class BaseImportItem {}

class BaseImport
{
    protected $em      = null;
    protected $excel   = null;
    protected $manager = null;
    
    public function __construct($excel,$manager)
    {
        $this->excel   = $excel;
        $this->manager = $manager;
        
        if ($manager instanceof EntityManager) $this->em = $manager;
        else                                   $this->em = $manager->getEntityManager();
        
        // Nice to know the class
        $importClass = get_class($this);
        $pos = strrpos($importClass,'\\');
        if ($pos === false) $pos = 0;
        else                $pos++;
        $this->importClass = substr($importClass,$pos);
    }
    /* =======================================
     * Result information
     */
    protected $importClass    = null;
    protected $inputFileName  = null;
    protected $clientFileName = null;
    protected $errors = array();
    protected $total    = 0;
    protected $inserted = 0;
    protected $updated  = 0;
    protected $deleted  = 0;

    public function postUpdate (LifecycleEventArgs $e) { $this->updated++;  }
    public function postRemove (LifecycleEventArgs $e) { $this->deleted++;  }
    public function postPersist(LifecycleEventArgs $e) { $this->inserted++; }

    protected function getResults()
    {
        // Main exit point
        $this->em->getEventManager()->removeEventListener(
            array(Events::postUpdate, Events::postRemove, Events::postPersist),
            $this);

        $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u",
            $this->importClass, $this->clientFileName,
            $this->total,$this->inserted,$this->updated);

        return array
        (
            'msg'           => $msg,
            'errors'        => $this->errors,
            'total'         => $this->total,
            'inserted'      => $this->inserted,
            'updated'       => $this->updated,
            'deleted'       => $this->deleted,
            'inputFileName' => $this->inputFileName,
            'importClass'   => $this->importClass,
        );
    }
    public function getResultMessage()
    {
        $results = $this->getResults();
        return $results['msg'];
    }

    /* ========================================
     * Maps header row to record
     */
    protected $record = array(
      // 'region'     => array('cols' => 'Region',         'req' => true,  'default' => 0),
    );
    protected $map = array();
    
    protected function processHeaderRow($row)
    {
        $found  = array();
        $record = $this->record;
        foreach($row as $index => $colName)
        {
            $colName = trim($colName);
            foreach($record as $name => $params)
            {
                if (is_array($params['cols'])) $cols = $params['cols'];
                else                           $cols = array($params['cols']);
                foreach($cols as $col)
                {
                    if ($col == $colName)
                    {
                        $this->map[$index] = $name;
                        $found[$name] = true;
                    }
                }
            }
        }

        // Make sure all required attributes found
        foreach($record as $name => $params)
        {
            if (isset($params['req']) && $params['req'])
            {
                if (!isset($found[$name]))
                {
                    if (is_array($params['cols'])) $cols = $params['cols'];
                    else                           $cols = array($params['cols']);
                    $cols = implode(' OR ',$cols);
                    $this->errors[] = "Missing $cols";
                }
            }
        }
    }
    /* ===========================================================
     * Process an opened file
     */
    protected function getSheetNames()
    {
        return array(null);
    }
    protected function processSheet($reader,$sheetName)
    {
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
    protected function processInputFile($inputFileName)
    {
        $reader = $this->excel->load($inputFileName);
        
        $sheetNames = $this->getSheetNames();
        
        foreach($sheetNames as $sheetName)
        {
            $this->processSheet($reader,$sheetName);
        }        
    }
    protected function processDataRow($row)
    {
        $item = new BaseImportItem();
        foreach($this->record as $name => $params)
        {
            if (isset($params['default'])) $default = $params['default'];
            else                           $default = null;
            $item->$name = $default;
        }
        foreach($row as $index => $value)
        {
            if (isset($this->map[$index]))
            {
                $name = $this->map[$index];
                $item->$name = trim($value);
            }
        }
        return $item;
    }
    protected function processItem($item) { return; }
    
    /* ==============================================
     * The normal way to kick off
     */
    protected $params = null;
    protected $projectid = null;
    protected $project = null;
    
    public function process($params = array())
    {
        // For tracking changes
        $this->em->getEventManager()->addEventListener(array(Events::postUpdate, Events::postRemove, Events::postPersist),$this);

        // Often have a project
        if (isset($params['projectId'])) $projectId = $params['projectId'];
        else                             $projectId = 0;

        if ($projectId)
        {
            $this->projectId = $projectId;
            $this->project = $this->em->getReference('ZaysoCoreBundle:Project',$projectId);
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

        // Process file
        $this->processInputFile($inputFileName);

        // Finish up
        $this->em->flush();
        $this->em->clear(); // Need for multiple files

        return $this->getResults();
    }
    /* ============================================================================
     * Some standard processing of input fields
     * Maybe should be moved somewhere else?
     * But it seems to work okay here
     */
    protected function processDate($date)
    {
        if (!$date) return null;
        
        // YYYYMMDD
        $datex = preg_replace('/\D/','',$date);
        if (($datex == $date) && (strlen($datex) == 8)) return $datex;
        
        // MM/DD/YY or MM/DD/YYYY
        // MM-DD-YY or MM-DD-YYYY
        $date = str_replace('-','/',$date);
        $parts = explode('/',$date);
        if (count($parts) == 3)
        {
            $year = (int)$parts[2];
            if ($year < 100)
            {
                if ($year > 20) $year += 1900; // Think this is backwards?
                else            $year += 2000;
            }
            $datex = sprintf('%04d%02d%02d',$year,(int)$parts[0],(int)$parts[1]); // die($datex);
            return $datex;
        }
         die('Date: ' . $date); // $dob = substr($dob,6,4) . substr($dob,0,2) . substr($dob,3,2);
    }
    protected function processTime($time)
    {
        if (!$time) return null;
        
        // HHMM
        $timex = preg_replace('/\D/','',$time);
        if (($timex == $time) && (strlen($timex) == 4)) return $timex;
        
        // HH:MM AM or PM
        $parts = explode(' ',$time);
        if (count($parts) == 2 && ($parts[1] == 'AM' || $parts[1] = 'PM'))
        {
            if ($parts[1] == 'PM') $offset = 12;
            else                   $offset =  0;
            
            $parts = explode(':',$parts[0]);
            if (count($parts) == 2)
            {
                // Noon = 12pm
                $hour = ((int)$parts[0] + $offset);
                if ($hour == 24) $hour = 12;
                
                $timex = sprintf('%02d%02d',$hour,(int)$parts[1]);
                return $timex;
            }
        }
         
        // HH:MM
        $parts = explode(':',$time);
        if (count($parts) == 2)
        {
            $timex = sprintf('%02d%02d',(int)$parts[0],(int)$parts[1]);
            return $timex;
        }
        die('Time: ' . $time);
    }
}
?>

<?php
namespace Zayso\ZaysoBundle\Component\Import;

class Item {}

class BaseImport
{
    protected $em = null;
    protected $record = array(
      // 'region'     => array('cols' => 'Region',         'req' => true,  'default' => 0),
    );
    protected $map = array();

    // Result information
    protected $importClass   = null;
    protected $inputFileName = null;
    protected $errors = array();
    protected $total    = 0;
    protected $inserted = 0;
    protected $updated  = 0;
    protected $deleted  = 0;

    public function postUpdate (\Doctrine\ORM\Event\LifecycleEventArgs $e) { $this->updated++;  }
    public function postRemove (\Doctrine\ORM\Event\LifecycleEventArgs $e) { $this->deleted++;  }
    public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $e) { $this->inserted++; }

    //
    public function __construct($em)
    {
        $this->em = $em;

        // Nice to know the class
        $importClass = get_class($this);
        $pos = strrpos($importClass,'\\');
        if ($pos === false) $pos = 0;
        else                $pos++;
        $this->importClass = substr($importClass,$pos);

        $this->init();
    }
    protected function init() {}

    protected function getEntityManager() { return $this->em; }

    protected function getResults()
    {
        // Main exit point
        $this->getEntityManager()->getEventManager()->removeEventListener(
            array(\Doctrine\ORM\Events::postUpdate, \Doctrine\ORM\Events::postRemove,\Doctrine\ORM\Events::postPersist),
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
        
        die('Need to refactor getResultMessage');
        $file  = basename($this->innFileName);
        $count = $this->count;
        $class = get_class($this);

        $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u",
            $class, $file,
            $count->total,$count->inserted,$count->updated);

        return $msg;
    }
    protected function processDataRow($row)
    {
        $item = new Item();
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
    protected function processHeaderRow($row)
    {
        $found  = array();
        $record = $this->record;
        foreach($row as $index => $colName)
        {
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
        // print_r($row);
        // print_r($this->map);
    }
    public function process($params = array())
    {
        // For tracking changes
        $this->getEntityManager()->getEventManager()->addEventListener(
            array(\Doctrine\ORM\Events::postUpdate, \Doctrine\ORM\Events::postRemove,\Doctrine\ORM\Events::postPersist),
            $this);

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

        // Open it
        $fp = fopen($inputFileName,'r');
        if (!$fp)
        {
            $this->errors[] = "Could not open $inputFileName";
            return $this->getResults();
        }
        // Process the header
        $header = fgetcsv($fp);
        $this->processHeaderRow($header);
        if (count($this->errors))
        {
            fclose($fp);
            return $this->getResults();
        }
        // Process the data
        while($row = fgetcsv($fp))
        {
            $item = $this->processDataRow($row);
            $this->processItem($item);
            $item = null;
            $row  = null;
        }
        // Finish up
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear(); // Need for multiple files

        fclose($fp);
        return $this->getResults();
    }
    public function processItem($item)
    {
        $this->total++;
    }
    /* ======================================================================
     * Assorted field specific processing
     */
    protected function processDate($date)
    {
        if (!$date) return '';
        
        // YYYYMMDD
        $datex = preg_replace('/\D/','',$date);
        if (($datex == $date) && (strlen($datex) == 8)) return $datex;
        
        // MM/DD/YY or MM/DD/YYYY
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
    protected function processPhone($phone)
    {
        return preg_replace('/\D/','',$phone);
    }
    protected function processName($name)
    {
        return ucfirst(strtolower($name));
    }
    protected function processEmail($email)
    {
        return strtolower($email);
    }
    protected function processMemYear($year)
    {
        $year = substr($year,-4);
        return (int)$year;
    }
}
?>

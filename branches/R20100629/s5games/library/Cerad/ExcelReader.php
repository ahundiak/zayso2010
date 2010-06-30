<?php
class Cerad_ExcelReader
{
    protected $map     = NULL;  // Filled in by child class
    protected $indexes = NULL;
    protected $headerRowProcessed = FALSE;
    public    $count = 0;
    protected $context = NULL;
    
    public function __construct($context,$fileName = NULL)
    {
        $this->context = $context;

        $this->init();
        
        if ($fileName) $this->process($fileName);    
    }
    public function init() {}
    
    public function getDb() { return $this->context->getDb(); }
    
    public function processRowHeader($cellNodes)
    {
        $map = $this->map;
        $indexes = array();
        for ($cellIndex = 0; $cellIndex < $cellNodes->length; $cellIndex++) {    
            $cellNode = $cellNodes->item($cellIndex);
            $dataNodes = $cellNode->getElementsByTagName('Data');
            $dataNode = $dataNodes->item(0);
            if ($dataNode) {
                $dataNodeValue = $dataNode->nodeValue;
                if (isset($map[$dataNodeValue])) $indexes[$cellIndex] = $map[$dataNodeValue];
                //echo "$cellIndex $dataNodeValue\n";
            }
        }
        // Verify all columns are present
        $flag = FALSE;
        foreach($map as $key => $value)
        {
            if (array_search($value,$indexes) === FALSE) 
            {    
                $flag = TRUE;
                echo "Missing index for $key\n";
            }
        }
        if ($flag) die();
        
        // Cleanup     
        $this->indexes = $indexes;
        $this->headerRowProcessed = TRUE;
    }
    public function processRow($cellNodes)
    {
        if (!$this->headerRowProcessed) return $this->processRowHeader($cellNodes);
        
        $indexes = $this->indexes;
        $data = array();
        foreach($indexes as $key => $value) {
            $data[$value] = NULL;
        }
        $dataIndex = 0;
        for ($cellIndex = 0; $cellIndex < $cellNodes->length; $cellIndex++) {    
            $cellNode  = $cellNodes->item($cellIndex);
            $cellNodeIndex = $cellNode->getAttribute('ss:Index');
            if ($cellNodeIndex) {
                $dataIndex = $cellNodeIndex - 1;
            }
            $dataNodes = $cellNode->getElementsByTagName('Data');
            $dataNode  = $dataNodes->item(0);
            if ($dataNode) {
                $dataNodeValue = trim($dataNode->nodeValue);
                if (isset($indexes[$dataIndex])) {
                    $data[$indexes[$dataIndex]] = $dataNodeValue;
                }
            }
            $dataIndex++;
        }
        // TODO: Add an option to return data as an object
        
        $this->processRowData($data);
        // $this->count++;
    }
    // Child class processor
    public function processRowData($data)
    {
        print_r($data);
        $this->count++;
        
        if ($this->count > 0) die();    
    }
    public function process($fileName)
    {
        $xmlReader = new XMLReader();
        $flag = $xmlReader->open($fileName);
        if (!$flag) die('Could not open $fileName');
        
        while($xmlReader->read()) {
            if ($xmlReader->nodeType == XMLReader::ELEMENT) {
                if ($xmlReader->name == 'Row') {
                    $rowNode = $xmlReader->expand();
                    $cellNodes = $rowNode->getElementsByTagName('Cell');
                    $this->processRow($cellNodes);
                }
            }
        }
        $xmlReader->close();
    }    
}
?>
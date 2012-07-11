<?php

namespace Zayso\CoreBundle\Monolog;

use Monolog\Handler\AbstractProcessingHandler;

class Handler extends AbstractProcessingHandler
{

    protected $pdo = null;
    
    public function __construct($pdo, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
        
        $this->pdo = $pdo;
    }
    protected function write(array $record)
    {
        $date = $record['datetime'];
        $dtg  = $date->format('Y-m-d H:i:s');
        
        echo sprintf("Log %s %s\n",$dtg,$record['message']);
        //print_r($record);
    }
}

?>

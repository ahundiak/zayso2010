<?php
class ExportBase
{
    protected $context = NULL;
    
    public function __construct($context)
    {
        $this->context = $context;
    }
}
?>
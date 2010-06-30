<?php
class Zayso_Model_Item_Base
{
	protected $data = NULL;
	
	function __construct($data = array(),$prefix = NULL)
	{
		if (!$prefix) {
			$this->data = $data;
			return;
		}
		$datax = array();
        $prefix .= '_';
        $prefixLen = strlen($prefix);
        foreach($data as $key => $value) 
        {
        	if (strpos($key,$prefix) === 0) {
            	$keyx = substr($key,$prefixLen);
                if ($keyx) $datax[$keyx] = $value;
            }    
        }
		$this->data = $datax;
	}
	function getDataAll() { return $this->data; }
	
	function getData($name,$default = NULL)
	{
		if (isset($this->data[$name])) return $this->data[$name];
		return $default;
	}
	function getId()
	{
		return $this->getData('id');
	}
}
?>
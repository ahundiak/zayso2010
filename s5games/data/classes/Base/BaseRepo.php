<?php
class Base_BaseRepo
{
	protected $context;
	
	public function __construct($context)
	{
		$this->context = $context;
	}
	protected function saveRow($table,$key,$data)
	{
		$db = $this->context->db;
		if (isset($data[$key])) $id = $data[$key];
		else                    $id = NULL;
    	
    	if ($id) $cnt = $db->update($table,$key,$data);
    	else     $cnt = $db->insert($table,$key,$data);
    	
    	if ($cnt != 1) return NULL;
    	
    	if ($id) return $id;
    	
    	return $db->lastInsertId();
 
	}
}
?>
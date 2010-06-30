<?php
class Zayso_Repo_Person
{
	protected $context;
	protected $db = NULL;
	
	function __construct($context)
	{
		$this->context = $context;
	}
	function getDb()
	{
		if (!$this->db) $this->db = $this->context->getDb();
		return $this->db;
	}
    public function findForAysoid($aysoid)
    {
        $db = $this->getDb();
        $data = $db->find('person','aysoid',$aysoid);
    	
        if ($data) {
        	$data['id'] = $data['person_id'];
        	unset($data['person_id']);
        }
        $item = new Zayso_Model_Item_Person($data);
        
        return $item;
        
        Cerad_Debug::dump($item);
    }
}
?>
<?php
class Zayso_Repo_Eayso
{
	protected $db = NULL;
	
	public function getDb()
    {
        if (!$this->db) {
            $dbParams = array (
                'host'     => '127.0.0.1',
                'username' => 'impd',
                'password' => 'impd894',
                'dbname'   => 'eayso',
                'dbtype'   => 'mysql',
                'adapter'  => 'pdo_mysql'
            );
            $this->db = new Cerad_DatabaseAdapter($dbParams);  
        }
        return $this->db;
    }
    public function findForAysoid($aysoid)
    {
        $db = $this->getDb();
        $data = $db->find('eayso_vol','aysoid',$aysoid);
    	
        if ($data) {
        	$data['id'] = $data['eayso_vol_id'];
        	unset($data['eayso_vol_id']);
        }
        $item = new Zayso_Model_Item_EaysoVol($data);
        
        return $item;
        
        Cerad_Debug::dump($item);
    }
	public function search($params)
	{
		$items = array();
		
		$where = NULL;
		foreach($params AS $key => $value)
		{
			if(strchr($value,'%')) $op = ' LIKE ';
			else                   $op = ' = ';
			
			if ($where) $where .= ' AND ';
			$where .= $key . $op . ':' . $key;
		}
		if (!$where) return $items;
		$sql = 'SELECT * FROM eayso_vol WHERE ' . $where . ';';
		echo $sql . "\n";
		
		$db = $this->getDb();
		$rows = $db->fetchRows($sql,$params);
		foreach ($rows as $row)
		{
			$items[] = new Zayso_Model_Item_EaysoVol($row);
		}
		// Cerad_Debug::dump($items);
		return $items;
	}
}
?>
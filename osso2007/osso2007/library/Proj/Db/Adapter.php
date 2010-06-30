<?php
/* =======================
 * Don't like the direction Zend is taking as far as database stuff goes so see what it takes
 * to build a simple wrapper for the pdo object
 */
class Proj_Db_Adapter
{
    protected $pdo = NULL;
    protected $dbParams = array();
    
    function __construct($dbParams)
    {
        $this->dbParams = $dbParams;
        
        $conn = "{$dbParams['dbtype']}:host={$dbParams['host']};dbname={$dbParams['dbname']}";
        
        $this->pdo = $pdo = new PDO($conn,$dbParams['username'],$dbParams['password']);  
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // PDO::ERRMODE_SILENT
        
        //$dbParams = array (
        //    'host'     => '127.0.0.1',
        //    'username' => 'impd',
        //    'password' => 'impd894',
        //    'dbname'   => 'osso2007',
        //    'dbtype'   => 'mysql',
        //    'adapter'  => 'pdo_mysql'
        //);  
    }
    public function quote($values)
    {
        if (!is_array($values)) return $this->pdo->quote($values);
        
        $pdo  = $this->pdo;
        $text = NULL;
        foreach($values as $value) {
            if ($text)          $text .= ',';
            if (is_int($value)) $text .= $value;
            else                $text .= $pdo->quote($value);    
        }
        return $text;
    }
    public function quoteInto($text, $value)
    {
        return str_replace('?', $this->quote($value), $text);
    }
    function fetchAll($sql,$values = NULL)
    {
        if (is_object($sql)) $sql = $sql->__toString();
        
        if (!$values) $stmt = $this->pdo->query($sql);
        else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Always an array
    }
    function fetchOne($sql,$values = NULL)
    {
        if (is_object($sql)) $sql = $sql->__toString();
        
        if (!$values) $stmt = $this->pdo->query($sql);
        else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC); // FALSE if not found
    }
    function fetchRow ($sql,$values = NULL) { return $this->fetchOne($sql,$values); }
    function fetchRows($sql,$values = NULL) { return $this->fetchAll($sql,$values); }
    
    function fetchCol($sql,$values = NULL)
    {
        if (is_object($sql)) $sql = $sql->__toString();
        
        if (!$values) $stmt = $this->pdo->query($sql);
        else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
        return $stmt->fetchColumn(); // Probably FALSE if not found
    }
    function find($tableName,$keyName,$data)
    {
        if (!is_array($data)) $data = array($keyName => $data);
        
        $sql = "SELECT * FROM {$tableName} WHERE {$keyName} = :{$keyName};";

        return $this->fetchOne($sql,$data);
    }
    function delete($tableName,$keyName,$id)
    {
        if (!$id) return 0;
        
        $id = $this->quote($id);
     
        if (!$id) return 0;
        
        $sql = "DELETE FROM {$tableName} WHERE {$keyName} IN ({$id});";

        return $this->pdo->exec($sql);
    }
    function update($tableName,$keyName,$data)
    {
        // Build set list
        $set = '';
        foreach(array_keys($data) as $name) {
            if ($name != $keyName) {
                if ($set) $set .= ', ';
                $set .= $name . ' = :' . $name;
            }
        }
        $sql = "UPDATE {$tableName}\nSET {$set}\nWHERE {$keyName} = :{$keyName};";

        $stmt = $this->pdo->prepare($sql);
        
        $cnt = $stmt->execute($data);
        
        return $cnt;
    }
    function insert($tableName,$keyName,$data)
    {
        // Build set list
        $names  = '';
        $values = '';
        foreach(array_keys($data) as $name) {
            if ($names)  $names  .= ', ';
            if ($values) $values .= ', ';
            $names  .= ' ' . $name;
            $values .= ':' . $name;    
        }
        $sql = "INSERT INTO {$tableName}\n({$names}) VALUES \n({$values});";
    
        $stmt = $this->pdo->prepare($sql);
        
        $cnt = $stmt->execute($data);
        
        return $cnt;
    }
    function lastInsertId() { return $this->pdo->lastInsertId(); } 
    
    function isDuplicateEntryError($e)
    {
        $errorCode = $e->errorInfo[1];
        
        // Zend::dump($e->errorInfo);
        
        
        if ($errorCode == '1062') return TRUE; // 1048 is cannot be NULL
        return FALSE;
    }   
}
?>

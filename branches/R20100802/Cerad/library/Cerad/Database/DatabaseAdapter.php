<?php
class Cerad_Database_Adapter
{   
  protected $pdo = NULL;
  protected $dbParams = array();
    
  function __construct($dbParams = NULL)
  {
    if (!$dbParams) die('DatabaseAdapter called with no parameters');
    $this->dbParams = $dbParams;
        
    $conn = "{$dbParams['dbtype']}:host={$dbParams['host']};dbname={$dbParams['dbname']}";
        
    $this->pdo = $pdo = new PDO($conn,$dbParams['username'],$dbParams['password']);  
        
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION); // PDO::ERRMODE_SILENT
    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);    
    $this->pdo->setAttribute(PDO::ATTR_CASE,               PDO::CASE_LOWER); 
    $this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS,       PDO::NULL_TO_STRING);
  }
  public function quote($values)
  {
    if (!is_array($values)) return $this->pdo->quote($values);
        
    $pdo  = $this->pdo;
    $text = NULL;
    foreach($values as $value)
    {
      if ($text)          $text .= ',';
      if (is_int($value)) $text .= $value;
      else                $text .= $pdo->quote($value); // No nested arrays for now
    }
    return $text;
  }
  public function quoteInto($text, $value)
  {
    return str_replace('?', $this->quote($value), $text);
  }
  function prepare($sql,$values = NULL)
  {
    if (is_object($sql)) $sql = $sql->__toString();

    if (!$values) $stmt = $this->pdo->query($sql);
    else
    {
      $valuesx = $values;
      foreach($values as $key => $value)
      {
        if (is_array($value))
        {
          $value = '(' . $this->quote($value) . ')';
          $keyx  = "(:{$key})";
          $sql = str_replace($keyx,$value,$sql);
          unset($valuesx[$key]);
        }
      }
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($valuesx);
    }
    return $stmt;
  }
  function fetchRows($sql,$values = NULL)
  {
    $stmt = $this->prepare($sql,$values);
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Always an array
  }
  function fetchRow($sql,$values = NULL)
  {
    $stmt = $this->prepare($sql,$values);
    return $stmt->fetch(PDO::FETCH_ASSOC); // FALSE if not found
  }
  function fetchCol($sql,$values = NULL)
  {
    $stmt = $this->prepare($sql,$values);
    return $stmt->fetchColumn(); // Probably FALSE if not found
  }
  function execute($sql,$data = NULL)
  {
    $stmt = $this->prepare($sql,$data);
    return $stmt->rowCount();
  }
  function find($tableName,$keyName,$data)
  {
    if (!is_array($data)) $data = array($keyName => $data);
        
    $sql = "SELECT * FROM {$tableName} WHERE {$keyName} = :{$keyName};";

    return $this->fetchRow($sql,$data);
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
    foreach(array_keys($data) as $name)
    {
      if ($name != $keyName)
      {
        if ($set) $set .= ', ';
        $set .= $name . ' = :' . $name;
      }
    }
    $sql = "UPDATE {$tableName}\nSET {$set}\nWHERE {$keyName} = :{$keyName};";

    $stmt = $this->pdo->prepare($sql);
        
    $stmt->execute($data);
        
    return $stmt->rowCount();
  }
  function insert($tableName,$keyName,$data,$ignoreDupKey = false)
  {
    // Build set list
    $names  = '';
    $values = '';
    foreach(array_keys($data) as $name)
    {
      if ($names)  $names  .= ', ';
      if ($values) $values .= ', ';
      $names  .= ' ' . $name;
      $values .= ':' . $name;    
    }
    if (!$ignoreDupKey) $dup = NULL;
    else $dup = "ON DUPLICATE KEY UPDATE {$keyName} = {$keyName}";

    $sql = "INSERT INTO {$tableName}\n({$names}) VALUES \n({$values}) {$dup};";
    
    $stmt = $this->pdo->prepare($sql);
        
    $stmt->execute($data);
        
    return $stmt->rowCount();
  }
  function lastInsertId() { return $this->pdo->lastInsertId(); } 
    
  function isDuplicateEntryError($e)
  {
    $errorCode = $e->errorInfo[1];
        
        // Zend_Debug::dump($e->errorInfo);
        
    if ($errorCode == '1062') return TRUE; // 1048 is cannot be NULL
    return FALSE;
  }   
}
?>

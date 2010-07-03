<?php
class Cerad_Direct_Base
{
  protected $context;
  
  protected $db;
  protected $dbName  = '';

  protected $tblName = '';
  protected $idName  = 'id';

  protected $ignoreDupKey = false;

  protected $resultClassName = 'Cerad_Direct_Result';

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    if ($this->dbName)
    {
      $dbName = $this->dbName;
      $this->db = $this->context->$dbName;
    }
  }
  protected function newResult() { return new $this->resultClassName(); }
  
  public function insert($params)
  {
    $result = $this->newResult();
    try
    {
      $result->count = $this->db->insert($this->tblName,$this->idName,$params,$this->ignoreDupKey);
    }
    catch (Exception $e)
    {
      $dup = $this->db->isDuplicateEntryError($e);

      if ($dup) $error = 'Duplicate Key';
      else      $error = 'Unknown insert error';

      $result->error = $error;

      if (!$dup)
      {
        echo "{$e->getMessage()}\n";  // TODOx Add a global flag for this
      }
      return $result;
    }
    $result->id = $this->db->lastInsertId();

    return $result;
  }
  public function update($params)
  {
    $result = $this->newResult();

    $result->count = $this->db->update($this->tblName,$this->idName,$params);

    return $result;
  }
  // This should also handle generic search parameters
  // But at least id can be an arrray
  public function delete($id)
  {
    $result = $this->newResult();
    $result->count = $this->db->delete($this->tblName,$this->idName,$id);

    return $result;
  }
  public function fetchRows($params)
  {
    $result = $this->newResult();

    $where = NULL;
    foreach($params AS $key => $value)
    {
      if (is_array($value)) $compare = "$key IN(:$key)";
      else                  $compare = "$key =  :$key";

      if ($where) $where .= ' AND '  . $compare;
      else        $where  = 'WHERE ' . $compare;
    }

    $sql = "SELECT * FROM {$this->tblName} {$where};";

    try
    {
      $rows = $this->db->fetchRows($sql,$params);
    }
    catch (Exception $e)
    {
      $result->error = $e->getMessage();
      $result->sql = $sql;
      echo "{$e->getMessage()}\n{$sql}\n";  // TODOx Add a global flag for this
      return $result;
    }
    $result->rows = $rows;
    return $result;
  }
  public function fetchRow($params)
  {
    $result = $this->fetchRows($params); // Cerad_Debug::dump($result); die();
    
    $rows = $result->rows;
    $result->rows = NULL;
    if (count($rows)) $result->row = $rows[0];

    return $result;
  }
}
?>

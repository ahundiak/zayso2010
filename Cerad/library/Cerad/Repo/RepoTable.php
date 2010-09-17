<?php
class Cerad_Repo_RepoTable
{
  protected $db;

  public $name;   // Full table name
  public $pk;     // Primary key column name
  public $types;  // Column types
  public $values; // Default values
  public $raw;    // Raw information returned by the query

  public function __construct($db,$name)
  {
    $this->db = $db;

    $this->name = $name;

    $sql  = "DESCRIBE $name;";
    $this->raw = $rows = $db->fetchRows($sql);

    $values = array();
    $types  = array();

    foreach($rows as $row)
    {
      $name = $row['field'];

      if ($row['key'] == 'PRI') $this->pk = $name;

      $type = substr($row['type'],0,3);
      if ($type == 'int') $value = (int)$row['default'];
      else                $value =      $row['default'];

      $values[$name] = $value;
      $types [$name] = $row['type'];

    }
    $this->values = $values;
    $this->types  = $types;
  }
  /* ---------------------------------------------------------
   * Pull columns from data fields and overwrite default values
   * Call prior to adding a new record
   */
  public function merge($data)
  {
    $values = $this->values;
    foreach($values AS $key => $value)
    {
      if (isset($data[$key])) $values[$key] = $data[$key];
    }
    return $values;
  }
  /* ---------------------------------------------------------
   * Pulls out valid column names
   */
  public function filter($data)
  {
    $values = array();
    foreach($this->values AS $key => $value)
    {
      if (isset($data[$key])) $values[$key] = $data[$key];
    }
    return $values;
  }
  /* ---------------------------------------------------------
   * data 1 is typically a row returned from a previous query
   * data 2 contains potientally new data
   * Compare the two and return an array containing any differences
   *
   * Transfer the id if there are any changes
   *
   * Returns null if no changes
   */
  public function changes($data1,$data2)
  {
    $changes = null;
    foreach($data1 AS $key => $value)
    {
      if (isset($data2[$key]))
      {
        if ($data2[$key] != $value) $changes[$key] = $data2[$key];
      }
    }
    if ($changes) $changes[$this->pk] = $data1[$this->pk];
    return $changes;
  }
  public function insert($data)
  {
    if (!is_array($data)) return 0;
    
    $data = $this->merge($data);
    unset($data[$this->pk]);
    $count = $this->db->insert($this->name,$this->pk,$data);

    if ($count) return $this->db->lastInsertId();

    return 0;
  }
  public function update($data)
  {
    if (!is_array($data)) return 0;

    $data = $this->filter($data);
    $count = $this->db->update($this->name,$this->pk,$data);

    if ($count) return $data[$this->pk];
    return 0;
  }
  public function fetch($id)
  {
    $sql = "SELECT * FROM {$this->name} WHERE {$this->pk} = :id";
    $rows = $this->db->fetchRows($sql,array('id' => $id));
    if (isset($rows[0])) return $rows[0];
    return null;
  }
  public function save($data)
  {
    $pk = $this->pk;

    if (!isset($data[$pk]) || !$data[$pk]) return $this->insert($data);

    // Could fetch out the record and only save the changes

    // But maybe not
    return $this->update($data);
  }
  public function query($cols = null,$wheres = null)
  {
    if ($cols == null) $list = '*';
    else               $list = $cols;
    if (is_array($cols) && count($cols) > 0)
    {
      $list = NULL;
      foreach($cols as $key => $name)
      {
        if (is_int($key)) $namex = $name;
        else              $namex = "$key AS $name";

        if ($list) $list .= ',' . $namex;
        else       $list  =       $namex;
      }
      $cols = $list;
    }
    $cols = $list;

    $search = array();
    if (is_array($wheres) && count($wheres) > 0)
    {
      $wheresx = array();
      foreach($wheres as $key => $value)
      {
        if (!is_array($value))
        {
          $wheresx[] = "$key = :$key";
          $search[$key] = $value;
        }
        else
        {
          $value = $this->db->quote($value);
          $wheres[$key] = $value;
          $wheresx[] = "$key IN ($value)";
        }
      }
      $where = implode(' AND ',$wheresx);
    }
    $sql = "SELECT {$cols} FROM {$this->name} ";
    if ($where) $sql .= ' WHERE ' . $where;
    $sql .= ';';
  //echo $sql . "\n";
    return $this->db->fetchRows($sql,$search);
  }
}
?>

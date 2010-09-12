<?php
class Cerad_Repo_RepoBase
{
  protected $context;
  protected $db;

  protected $tableName;
//protected $table;  // Created first time accessed

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->db = $this->context->db;
  }
  public function __get($name)
  {
    switch($name)
    {
      case 'result': return new Cerad_Repo_RepoResult();

      case 'table':
        return $this->table = new Cerad_Repo_RepoTable($this->db,$this->tableName);
    }
  }
}
?>

<?php
class Org_Import_Unit
{
  protected $context;
  protected $reader;

  protected $totalCount = 0;

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->reader = new Org_Import_UnitReader($this->context,NULL,$this);
    $this->db = $this->context->dbOsso;
  }
  public function process($innFileName)
  {
    return $this->reader->process($innFileName);
  }
  public function getResultMessage()
  {
    $errors = $this->reader->getErrors();
    if (count($errors)) { Cerad_Debug::dump($errors); echo count($errors); die('Have errors'); }
    $msg = sprintf("Import unit, Total %u",$this->totalCount);
    return $msg;
  }
  public function processRowData($data)
  {
    $this->totalCount++;

    // Cerad_Debug::dump($data);

    $data['keyxx'] = $data['keyx'];

    if ($data['org_type_id'] != 4) $data['keyx'] = $data['abbv'];

    $db = $this->db;

    $item = $db->find('org','keyx',$data['keyx']);

    if ($item) $db->update('org','id',$data);
    else       $db->insert('org','id',$data);
  }
}

?>

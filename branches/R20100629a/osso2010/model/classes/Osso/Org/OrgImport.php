<?php
class Org_Import_Org extends Cerad_Import
{
  protected $readerClassName = 'Org_Import_OrgReader';

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso;
    $this->ts = $this->context->getTimeStamp();
  }
  public function getResultMessage()
  {
    $errors = $this->reader->getErrors();
    if (count($errors)) { Cerad_Debug::dump($errors); echo count($errors); die('Have errors'); }

    $file = basename($this->innFileName);

    $msg = sprintf("Import org %s, Total %u, Inserted %u, Updated %u",
      $file,$this->countTotal,$this->countInsert,$this->countUpdate);
    return $msg;
  }
  public function processRowData($data)
  {
    $this->totalCount++;
    $db = $this->db;

    // See if have existing entry
    $id = (int)$data['id'];
    $datax = $db->find('org','id',$id);
    if (!$datax)
    {
      $db->insert('org','id',$data);
      $this->countInsert++;
      return;
    }
    // Update if necessary
    $datax['id'] = $id;
    $changed = array();
    foreach($data as $key => $value)
    {
      if ($datax[$key] != $value) $changed[$key] = $value;
    }
    if (count($changed))
    {
      $changed['id'] = $id;
      $db->update('org','id',$changed);
      $this->countUpdate++;
      return;
    }
  }
}
?>

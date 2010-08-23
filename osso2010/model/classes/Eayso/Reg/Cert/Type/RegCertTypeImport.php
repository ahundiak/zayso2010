<?php
class Eayso_Reg_Cert_Type_RegCertTypeImport extends Cerad_Import
{
  protected $readerClassName = 'Eayso_Reg_Cert_Type_RegCertTypeReader';

  protected function init()
  {
    parent::init();
    
    $this->directRegCertType = new Eayso_Reg_Cert_Type_RegCertTypeDirect($this->context);

  }
  public function getResultMessage()
  {
    $file  = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u",
      $class, $file,
      $count->total,$count->inserted,$count->updated);

    return $msg;
  }
  public function processRowData($data)
  {
    if ( !$data['id']) return;
    unset($data['fake']);

    $this->count->total++;

    $this->directRegCertType->insert($data);
  }
}
?>
<?php
class Osso2007_Site_FieldImport extends Cerad_Import
{
  protected $readerClassName = 'Osso2007_Site_FieldReader';
  protected $regions = array();

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso;
    $this->ts = $this->context->getTimeStamp();

    $this->count->inserted = 0;
    $this->directField      = new Osso_Site_SiteFieldDirect     ($this->context);
    $this->directFieldAlias = new Osso_Site_SiteFieldAliasDirect($this->context);

  }
  public function getResultMessage()
  {
    $file = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Inserted: %u\n",
      $class, $file,
      $count->total,$count->inserted);
    return $msg;
  }
  public function processRowData($data)
  {
    $db = $this->db;
    
    $id = $data['id'];
    if (!$id) return;

    $this->directField->insert($data);
    $this->count->inserted++;

    $datax = array
    (
      'site_field_id' => $id,
      'alias'         => $data['descx'],
      'master'        => 1,
    );
    $this->directFieldAlias->insert($datax);
  }
}
?>

<?php
class Osso2007_Site_SiteImport extends Cerad_Import
{
  protected $readerClassName = 'Osso2007_Site_SiteReader';
  protected $regions = array();

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso;
    $this->ts = $this->context->getTimeStamp();

    $this->count->inserted = 0;
    $this->directSite = new Osso_Site_SiteDirect($this->context);

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

    $this->directSite->insert($data);
    $this->count->inserted++;
  }
}
?>

<?php
/* 
 * Merge in master list of regions after perserving original osso2007
 * Organizations
 */
class Org_Import_Region
{
  protected $context;
  protected $reader;

  protected $totalCount  = 0;
  protected $insertCount = 0;

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->reader = new Org_Import_RegionReader($this->context,NULL,$this);
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
    $msg = sprintf("Import Regions, Total %u, Inserted %u",$this->totalCount,$this->insertCount);
    return $msg;
  }
  public function processRowData($data)
  {
    $this->totalCount++;
    $db = $this->db;
    
    // Start by building standard region key
    $region = $data['region'];
    if (!$region) return;
    
    $keyx = 'R' . sprintf('%04u',$region);

    // Not going to mess with existing records
    $item = $db->find('org','keyx',$keyx);
    if ($item) return;

    // Now build some sort of desc
    $cities = $data['cities'];
    $cities = explode(',',$cities);
    $desc = NULL;
    for($i = 0; $i < 3; $i++)
    {
      if (isset($cities[$i]))
      {
        if ($desc) $desc .= ',' . $cities[$i];
        else       $desc  =       $cities[$i];
      }
    }
    $desc2 = $desc;
    $desc1 = $keyx . ' ' . $desc;
    $desc1 = substr($desc1,0,30);

    $datax = array();
    $datax['keyx' ]       = $keyx;
    $datax['keyxx']       = $keyx;
    $datax['abbv' ]       = $keyx;
    $datax['org_type_id'] = 4;
    $datax['desc1']       = $desc1;
    $datax['desc2']       = $desc2;

    $db->insert('org','id',$datax);
    $this->insertCount++;

    return;
    
    // Cerad_Debug::dump($data);

    $data['keyxx'] = $data['keyx'];

    if ($data['type_id'] != 4) $data['keyx'] = $data['abbv'];

    $db = $this->db;

    $item = $db->find('org','keyx',$data['keyx']);

    if ($item) $db->update('org','id',$data);
    else       $db->insert('org','id',$data);
  }
}
?>

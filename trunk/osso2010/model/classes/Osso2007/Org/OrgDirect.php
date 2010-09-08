<?php
class Osso2007_Org_OrgDirect extends Cerad_Direct_Base
{
  protected $tblName = 'osso2007.unit';
  protected $idName  = 'unit_id';
  protected $dbName  = 'dbOsso2007';

  public function getOrgForKey($keyx)
  {

    $result = new Cerad_Direct_Result();
    
    if (is_array($keyx)) $keyx = $keyx['keyx'];

    $num = (int)$keyx;
    if ($num) $keyx = sprintf('R%04u',$keyx);

    $search = array('keyx' => $keyx);
    $row = $this->db->find('unit','keyx',$search);

    $result->row = $row;

    return $result;
  }
}
?>

<?php
class Osso2007_Site_FieldDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'osso2007.field';
  protected $idName  = 'field_id';
  protected $ignoreDupKey = true;

  protected $directFieldAlias = null;
  
  public function getField($params)
  {
    if (is_array($params)) $name = $params['name'];
    else                   $name = $params;

    if (!$this->directFieldAlias) $this->directFieldAlias = new Osso2007_Site_FieldAliasDirect($this->context);

    $namex = $this->directFieldAlias->processAlias($name);
  
    $result = $this->fetchRow(array('descx' => $namex));

    return $result;
  }
}
?>

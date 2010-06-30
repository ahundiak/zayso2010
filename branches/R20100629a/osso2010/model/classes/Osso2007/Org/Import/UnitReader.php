<?php
class Org_Import_UnitReader extends Cerad_Reader_CSV
{
  protected $map  = array
  (
    'unit_id'      => 'id',
    'unit_type_id' => 'org_type_id',
    'keyx'         => 'keyx',
    'prefix'       => 'abbv',
    'desc_pick'    => 'desc1',
    'desc_long'    => 'desc2',
  );
}
?>

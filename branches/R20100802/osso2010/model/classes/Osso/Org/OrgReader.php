<?php
class Org_Import_OrgReader extends Cerad_Reader_CSV
{
  protected $map  = array
  (
    'id'          => 'id',
    'org_type_id' => 'org_type_id',
    'keyx'        => 'keyx',
    'keyxx'       => 'keyxx',
    'abbv'        => 'abbv',
    'desc1'       => 'desc1',
    'desc2'       => 'desc2',
  );
}
?>

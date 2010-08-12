<?php
class Osso2007_Site_FieldReader extends Cerad_Reader_CSV
{
  // field_id	field_site_id	unit_id	keyx	sortx	age	descx	status

  protected $map = array
  (
    'field_id'      => 'id',
    'field_site_id' => 'site_id',
    'unit_id'       => 'org_id',
    'age'           => 'age',
    'descx'         => 'descx',
    'status'        => 'status',
  );
}
?>

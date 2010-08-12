<?php
class Osso2007_Site_SiteReader extends Cerad_Reader_CSV
{
  // field_site_id	unit_id	keyx	sortx	descx	status

  protected $map = array
  (
    'field_site_id' => 'id',
    'unit_id'       => 'org_id',
    'descx'         => 'descx',
    'status'        => 'status',
  );
}
?>

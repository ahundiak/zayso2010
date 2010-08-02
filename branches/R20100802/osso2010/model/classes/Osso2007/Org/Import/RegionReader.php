<?php
class Org_Import_RegionReader extends Cerad_Reader_CSV
{
  protected $map  = array
  (
    'Region'      => 'region',
    'Section'     => 'section',
    'Area'        => 'area',
    'State'       => 'state',
    'Desc1'       => 'desc1',
    'COMMUNITIES' => 'cities',
  );
}
?>

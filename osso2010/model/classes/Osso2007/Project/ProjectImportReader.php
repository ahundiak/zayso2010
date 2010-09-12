<?php

class Osso2007_Project_ProjectImportReader extends Cerad_Reader_CSV
{
  protected $map = array
  (
    'id'          => 'id',
    'status'      => 'status',
    'event_num'   => 'event_num',
    'sport'       => 'sport_type_id',
    'mem_year'    => 'mem_year',
    'cal_year'    => 'cal_year',
    'season'      => 'season_type_id',
    'type'        => 'type_id',
    'admin'       => 'admin_org_id',
    'description' => 'desc1',
    'date_beg'    => 'date_beg',
    'date_end'    => 'date_end',
    'regions'     => 'regions',
  );
}
?>

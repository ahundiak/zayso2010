<?php
class Osso2007_FrontEnd_Context extends Cerad_Context
{
  protected $classNames = array
  (
    'db'       => 'Cerad_DatabaseAdapter',

    'request'  => 'Osso2007_Request',
    'response' => 'Osso2007_Response',
    'session'  => 'Osso2007_Session',

    'url'      => 'Osso2007_Url',
    'html'     => 'Cerad_HTML',

    'repos'    => 'Osso2007_Repos',
    'tables'   => 'Osso2007_Tables',
  );
}

?>

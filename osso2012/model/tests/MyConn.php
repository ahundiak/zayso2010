<?php
class MyConn extends \Doctrine\DBAL\Connection
{
    public function executeQueryx($query, array $params = array(), $types = array())
    {
      print_r($params);
      die($query);
    }
}

?>

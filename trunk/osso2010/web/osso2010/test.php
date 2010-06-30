<?php
$params = json_decode($_POST['params'],true);
/*
$paramsx = array();
foreach($params as $param)
{
  $paramsx[$param['name']] = $param['value'];
}*/
$date2 = $params['date2'];

$data = array
(
  'success' => true,
  'msg' => 'It worked ' . $date2,
);

header('text/js');
echo json_encode($data);
// var_dump($_POST);
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$jsonData = '[ ' .
  '{ "id": "Podiceps nigricollis", "label": "Black-necked Grebe", "value": "Black-necked Grebe" }, ' .
  '{ "id": "Nycticorax nycticorax", "label": "Black-crowned Night Heron", "value": "Black-crowned Night Heron" }, ' .
  '{ "id": "Tetrao tetrix", "label": "Black Grouse", "value": "Black Grouse" }, ' .
  '{ "id": "Limosa limosa", "label": "Black-tailed Godwit", "value": "Black-tailed Godwit" }, ' .
  '{ "id": "Chlidonias niger", "label": "Black Tern", "value": "Black Tern" }, ' .
  '{ "id": "Locustella luscinioides", "label": "Savi`s Warbler", "value": "Savi`s Warbler" }, ' .
  '{ "id": "Acrocephalus schoenobaenus", "label": "Sedge Warbler", "value": "Sedge Warbler" }, ' .
  '{ "id": "Acrocephalus arundinaceus", "label": "Great Reed Warbler", "value": "Great Reed Warbler" }, ' .
  '{ "id": "Luscinia svecica", "label": "Bluethroat", "value": "Bluethroat" }, ' .
  '{ "id": "Acrocephalus palustris", "label": "Marsh Warbler", "value": "Marsh Warbler" }, ' .
  '{ "id": "Phylloscopus trochilus", "label": "Willow Warbler", "value": "Willow Warbler" }, ' .
  '{ "id": "Phylloscopus sibilatrix", "label": "Wood Warbler", "value": "Wood Warbler" } ]';

$birds = json_decode($jsonData,true);

if (isset($_GET['term'])) $term = trim($_GET['term']);
else                      $term = 'NOPE';

$data = array();
foreach($birds as $bird)
{
  if (stristr($bird['label'],$term) !== FALSE) $data[] = $bird;
}
header('Content-type: text/json');

echo json_encode($data);

?>

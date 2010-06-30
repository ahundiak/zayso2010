<?php
$results = array('success' => true, 'name' => 'NONE');

if (isset($_FILES['myfile']))
{
  $results['name'] = $_FILES['myfile']['name'];
}

header('text/js');
echo json_encode($results);

// var_dump($_FILES);
?>

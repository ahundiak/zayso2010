<?php
error_reporting(E_ALL);
// $url = 'index.html.php';
//header('Location: ' . $url);

if (isset($_GET['page'])) $page = $_GET['page'];
else                      $page = 'index';

ob_start();
include $page . '.html.php';
$content = ob_get_contents();
ob_end_clean();

include 'master.html.php';

?>

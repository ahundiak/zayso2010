<?php

session_start();

$tmpFileName = $_SESSION['tmpFileName'] . 'x';
$orgFileName = $_SESSION['orgFileName'];

$outFileName = basename($orgFileName,'.csv') . 'x.csv';

//echo "FILES: {$tmpFileName} {$orgFileName} {$outFileName}\n";
//return;

header('Content-type: text/csv');
header("Content-Disposition: attachment; filename=\"{$outFileName}\"");

// echo "FILES: {$tmpFileName} {$orgFileName} {$outFileName}\n";

readfile($tmpFileName);
unlink  ($tmpFileName);

// And remove it

?>

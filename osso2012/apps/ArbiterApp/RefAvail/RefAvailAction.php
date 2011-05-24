<?php
namespace ArbiterApp\RefAvail;

class RefAvailAction extends \ArbiterApp\FrontEnd\Action
{
  public function processGet($args)
  {
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

    return;
  }
  public function processPost($args)
  {
    session_start();
    date_default_timezone_set('US/Central');

    // Cerad_Debug::dump($_FILES); die();

    $name = 'ref_avail_file';

    if (!isset($_FILES[$name])) return $this->redirect('/');

    $orgFileName = $_FILES[$name]['name'];
    $tmpFileName = $_FILES[$name]['tmp_name'];

    $_SESSION['tmpFileName'] = $tmpFileName;
    $_SESSION['orgFileName'] = $orgFileName;

    $refAvail = new RefAvailProcess();

    $refAvail->importCSV($tmpFileName);
    $refAvail->exportCSV($tmpFileName . 'x');

    $this->redirect('ref_avail');
  }
}

?>

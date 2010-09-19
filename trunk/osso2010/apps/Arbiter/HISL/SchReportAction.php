<?php
class Arbiter_HISL_SchReportAction extends Cerad_FrontEnd_BaseAction
{
  public function executeGet($args)
  {
    session_start();

    $tmpFileName = $_SESSION['tmpFileName'] . 'x';
    $orgFileName = $_SESSION['orgFileName'];

    $outFileName = basename($orgFileName,'.csv') . 'Week.csv';

    //echo "FILES: {$tmpFileName} {$orgFileName} {$outFileName}\n";
    //return;

    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename=\"{$outFileName}\"");

    // echo "FILES: {$tmpFileName} {$orgFileName} {$outFileName}\n";

    readfile($tmpFileName);
    unlink  ($tmpFileName);

    return;
  }
  public function executePost($args)
  {
    session_start();
    date_default_timezone_set('US/Central');

    // Cerad_Debug::dump($_FILES); die();

    $name = 'input_file';

    if (!isset($_FILES[$name])) return $this->redirect('/');

    $orgFileName = $_FILES[$name]['name'];
    $tmpFileName = $_FILES[$name]['tmp_name'];

    $_SESSION['tmpFileName'] = $tmpFileName;
    $_SESSION['orgFileName'] = $orgFileName;

    $report = new Arbiter_HISL_SchReportProcess();
    $report->importCSV($tmpFileName);
    $report->exportCSV($tmpFileName . 'x');

    $this->redirect('hisl_sched_report');
    // die('Finished');
  }
}

?>

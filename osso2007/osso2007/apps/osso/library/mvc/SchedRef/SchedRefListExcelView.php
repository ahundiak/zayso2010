<?php
class SchedRefListExcelView extends Proj_View
{
  public function process($data)
  {
		
    $report = new Osso2007_Schedule_Referee_SchRefReport($this->context);
    $csv    = $report->process($data);
    return;

    header('Pragma: public');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
    header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
    header("Pragma: no-cache");
    header("Expires: 0");
    header('Content-Transfer-Encoding: none');
    header('Content-Type: application/vnd.ms-excel;'); // This should work for IE & Opera
    header("Content-type: application/x-msexcel"); // This should work for the rest
    header('Content-Type: text/csv;');
    header('Content-Disposition: attachment; filename="'. 'RefereeScgedule.csv' .'"');

    return $csv;
		
  }
}
?>
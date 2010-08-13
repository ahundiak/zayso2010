<?php
class Report_ReportTeamSummaryCSV
{
  protected $context = NULL;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function process($data)
  {
    $header = 'R0160 Huntsville,R0498 Madison, R0894 Monrovia, R1174 NEMC, R0557 SL/FAY';

    $divs = array(7,8,9,10,11,12,13,14,15,16,17,18,19,20,21);
    $directPhyTeam = new Osso2007_Phy_PhyTeamDirect($this->context);
    

    $report = <<<EOT
f1,f2,f3
1,two,3
EOT;
    return $report;
  }
}
?>

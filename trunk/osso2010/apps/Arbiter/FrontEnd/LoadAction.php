<?php
class Arbiter_FrontEnd_LoadAction extends Cerad_FrontEnd_LoadAction
{
  protected $argClassNames = array
  (
    'ref_avail'         => 'Arbiter_RefAvail_RefAvailAction',
    'hisl_sched_report' => 'Arbiter_HISL_SchReportAction',
    'hasl_pay'          => 'Arbiter_HASL_PayAction',
 );
}
?>

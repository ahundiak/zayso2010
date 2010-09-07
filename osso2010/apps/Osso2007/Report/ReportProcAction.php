<?php
error_reporting(E_ALL);

class Osso2007_Report_ReportProcAction extends Osso2007_Action
{
  public function processGet()
  {
    $session  = $this->context->session;

    if (isset($session->reportProcData)) $data = $session->reportProcData;
    else                                 $data = null;
    if (!$data || !is_array($data))
    {
      $user = $this->context->user;

      $data = array();
      $data['org_id']         = $user->unitId;
      $data['year_id']        = $user->yearId;
      $data['season_type_id'] = $user->seasonTypeId;
      $data['report_type_id'] = 0;
      $data['posted']         = false;

      $session->reportProcData = $data;
    }
         
    $view = new Osso2007_Report_ReportProcView($this->context);
    $view->process($data);

    // Wonder if this is better handled in the view?
    if ($data['posted'])
    {
      $data['posted'] = false;
      // $session->reportProcData = $data;
    }
    return;
  }
  public function processPost()
  {
    $request  = $this->context->request;
    $response = $this->context->response;
    $session  = $this->context->session;

    $redirect = 'report_proc';

    // Make sure authorized
    if (!$this->context->user->isAdminx)  return $response->setRedirect($redirect);

    // Pull data
    $data = array();
    $data['org_id']         = $request->getPostInt('report_unit_id');
    $data['year_id']        = $request->getPostInt('report_year_id');
    $data['season_type_id'] = $request->getPostInt('report_season_type_id');
    $data['report_type_id'] = $request->getPostInt('report_type_id');
    $data['posted']         = true;
    
    $session->reportProcData = $data;
    return $response->setRedirect($redirect);
    
  }
}
?>

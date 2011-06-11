<?php
namespace S5GamesApp\Schedule\Stats;

class StatsAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $services = $this->services;
    $session  = $services->session;
    $search   = $session->load('schedule-stats');
    
    $view = new StatsView($this->services);
    $view->process(clone $search);

    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $data = $session->load('account-list');

    // Extract
    $data->posted    = 1;
    $data->showFri   = $request->getPostInt('sched_show_fri');
    $data->showSat   = $request->getPostInt('sched_show_sat');
    $data->showSun   = $request->getPostInt('sched_show_sun');
    $data->showU10   = $request->getPostInt('sched_show_u10');
    $data->showU12   = $request->getPostInt('sched_show_u12');
    $data->showU14   = $request->getPostInt('sched_show_u14');
    $data->showU16   = $request->getPostInt('sched_show_u16');
    $data->showU19   = $request->getPostInt('sched_show_u19');
    $data->showCoed  = $request->getPostInt('sched_show_coed');
    $data->showGirl  = $request->getPostInt('sched_show_girl');
    
    $data->sort      = $request->getPostInt('sched_sort');
    $data->coach     = $request->getPostStr('sched_search_coach');
    $data->referee   = $request->getPostStr('sched_search_referee');

    $session->save($data);

    return $this->redirect('schedule-show');
  }
}
?>

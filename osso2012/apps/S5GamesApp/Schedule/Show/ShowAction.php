<?php
namespace S5GamesApp\Schedule\Show;

use \Cerad\DataItem as DataItem;

class ShowAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $services = $this->services;
    $session  = $services->session;
    $search   = $session->load('schedule-show');
    
    if (!$search->posted)
    {
      $search->showFri = 1;
      $search->showSat = 1;
      $search->showSun = 1;

      $search->showU10  = 1;
      $search->showU12  = 1;
      $search->showU14  = 1;
      $search->showU16  = 1;
      $search->showU19  = 1;
      $search->showCoed = 1;
      $search->showGirl = 1;

      $search->sort    = 1;
      $search->coach   = '';
      $search->referee = '';
    }
    if (isset($args[0])) $search->out = $args[0];
    else                 $search->out = 'web';

    $view = new ShowView($this->services);
    $view->process(clone $search);

    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $data = $session->load('schedule-show');

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

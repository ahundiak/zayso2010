<?php

namespace Zayso\NatGamesBundle\Controller\Admin\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;

use Zayso\NatGamesBundle\Controller\Schedule\RefSchedController as BaseSchedController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefSchedController extends BaseSchedController
{
    protected $routeId  = 'zayso_core_admin_schedule_referee_list';
    
    protected $csvTpl   = 'Schedule:referee.csv.php';
    
    protected $excelTpl = 'Admin/Schedule:referee.excel.php';
    
    protected $htmlTpl  = 'Admin/Schedule:referee.html.twig';

}

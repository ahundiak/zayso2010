<?php

namespace Zayso\Area5CFBundle\Controller;

use Zayso\ZaysoBundle\Controller\BaseController as Controller;
use Zayso\ZaysoBundle\Component\Security\Core\User\User as User;
use Zayso\ZaysoBundle\Component\Format\HTML as FormatHTML;

class BaseController extends Controller
{
    public function getProjectId() { return 70; }
}
?>

<?php

namespace Zayso\Osso2007Bundle\Controller;
    
use Zayso\Osso2007Bundle\Component\User;
use Zayso\Osso2007Bundle\Component\Debug;
use Zayso\Osso2007Bundle\Component\HTML as FormatHTML;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected $user = null;

    protected function getGameManager()
    {
        return $this->get('game.manager');
    }
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }
    protected function getSession()
    {
        return $this->getRequest()->getSession();
    }
    protected function getProjectId()
    {
        return 70;
    }
    protected function getUser()
    {
        if ($this->user) return $this->user;

        $session = $this->getRequest()->getSession();
        $userData = $session->get('user');

        $userData['projectId'] = $this->getProjectId();

        $this->user = new User($this->getEntityManager(),$userData);
        return $this->user;
    }
    protected function getTplData()
    {
        $tplData = array
        (
            'user'   => $this->getUser(),
            'format' =>  new FormatHTML(),
        );
        return $tplData;
    }
}
?>

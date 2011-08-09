<?php

namespace Zayso\Area5CFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Zayso\ZaysoBundle\Component\Security\Core\User\User as User;
use Zayso\ZaysoBundle\Component\Format\HTML as FormatHTML;

class BaseController extends Controller
{
    protected $user = null;

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
        $userData = $session->get('userData');

        $accountId = 0;
        $memberId  = 0;
        $projectId = $this->getProjectId();

        if (isset($userData['accountId'])) $accountId = $userData['accountId'];
        if (isset($userData['memberId' ])) $memberId  = $userData['memberId'];
        if (isset($userData['projectId'])) $projectId = $userData['projectId'];

        $this->user = new User($this->getEntityManager());
        $this->user->load($accountId,$memberId,$projectId);
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

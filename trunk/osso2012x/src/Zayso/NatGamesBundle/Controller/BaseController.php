<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Zayso\ZaysoBundle\Component\Security\Core\User\User as User;
use Zayso\ZaysoBundle\Component\Format\HTML as FormatHTML;

class BaseController extends Controller
{
    // protected $user = null;

    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager('osso2012');
    }
    protected function getAccountManager()
    {
        return $this->get('account.manager');
    }
    protected function getSession(Request $request = null)
    {
        if ($request) return $request->getSession();
        return $this->getRequest()->getSession();
    }
    protected function getProjectId()
    {
        return 52;
    }
    // Be aware that this returns the string anon for non users
    protected function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }
    protected function setUser($userName)
    {
        $userProvider = $this->get('security.user.provider.zayso');
        $user = $userProvider->loadUserByUsername($userName);
        $providerKey = 'secured_area';
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->get('security.context')->setToken($token);
        return $user;
    }
    protected function getProjectPerson()
    {
        $accountManager = $this->getAccountManager();
        $user = $this->getUser();
        $params = array('personId' => $user->getPersonId(),'projectId' => $this->getProjectId());
        $projectPerson = $accountManager->getProjectPerson($params);
        return $projectPerson;
    }
    protected function getUserx()
    {
        if ($this->user) return $this->user;

        $session = $this->getRequest()->getSession();
//print_r($session->all()); die('attributes');
        $userData = $session->get('userData');
//print_r($userData); die();
        $accountId = 0;
        $memberId  = 0;
        $projectId = $this->getProjectId();

        if (isset($userData['accountId'])) $accountId = $userData['accountId'];
        if (isset($userData['memberId' ])) $memberId  = $userData['memberId'];
        if (isset($userData['projectId'])) $projectId = $userData['projectId'];

        $this->user = new User($this->container);
        $this->user->load($accountId,$memberId,$projectId);
        return $this->user;
    }
    protected function getTplData()
    {
        $tplData = array
        (
            // 'user'   => $this->getUser(),
            'format' =>  new FormatHTML(),
        );
        return $tplData;
    }
}
?>

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
        die('BaseController.getEntityManager');
        //return $this->getDoctrine()->getEntityManager('osso2012');
    }
    protected function getAccountManager()
    {
        return $this->get('zayso_natgames.account.manager');
    }
    protected function getSession(Request $request = null)
    {
        if ($request) return $request->getSession();
        return $this->getRequest()->getSession();
    }
    protected function getProjectId()
    {
        return $this->container->getParameter('zayso_core.project.master');
        return 52;
    }
    // Be aware that this returns the string anon for non users
    protected function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }
    // Takes either userName or an actual user object
    protected function setUser($userName)
    {
        if (is_object($userName)) $user = $userName;
        else
        {
            $userProvider = $this->get('zayso_core.user.provider');
            // Need try/catch here
            $user = $userProvider->loadUserByUsername($userName);
        }
        $providerKey = $this->container->getParameter('zayso_core.provider.key'); // secured_area
        
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
    protected function getTplData()
    {
        $tplData = array
        (
            // 'user'   => $this->getUser(),
            // 'format' =>  new FormatHTML(),
        );
        return $tplData;
    }
}
?>

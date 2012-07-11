<?php

namespace Zayso\ArbiterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseController extends Controller
{
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
        $providerKey = 'secured_area';
        $providerKey = $this->container->getParameter('zayso_area.provider.key'); // secured_area
        
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->get('security.context')->setToken($token);
        return $user;
    }
}
?>

<?php

namespace Zayso\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class WelcomeController extends Controller
{
    /* ====================================================
     * This appears to allow setting a given user bypassing
     * the login_check process
     * Firewall name (secured_area) is hardcoded
     */
    public function userAnnaAction()
    {
        $userProvider = $this->get('security.user.provider.zayso');
        $user = $userProvider->loadUserByUsername('Anna');
        $providerKey = 'secured_area';
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->get('security.context')->setToken($token);

        return $this->redirect($this->generateUrl('_welcome'));

    }
    public function indexAction()
    {
        // Symfony\Component\Security\Core\SecurityContext
        $secContext = $this->get('security.context');

        // UsernamePassword or AnonymousToken
        // Symfony\Component\Security\Core\Authentication\Token\AnonymousToken
        $token = $this->get('security.context')->getToken();

        $tokenKey   = 'Token Key';
        $tokenCreds = 'Token Creds';
        if ($token instanceof AnonymousToken)
        {
            $tokenKey = $token->getKey(); // 4ec514afdc1fb
        }
        if ($token instanceof UsernamePasswordToken)
        {
            $tokenKey   = $token->getProviderKey(); // secured_area
            $tokenCreds = $token->getCredentials();
        }
        $user = $this->get('security.context')->getToken()->getUser();

        $tplData = array();

        if (is_object($user)) $tplData['userClass'] = get_class($user);
        else                  $tplData['userClass'] = 'UC ' . $user; // anon if not signed in

        if (is_object($user)) $tplData['userName'] = $user->getUsername();
        else                  $tplData['userName'] = 'Guest';

        if (is_object($token)) $tplData['tokenClass'] = get_class($token);
        else                   $tplData['tokenClass'] = 'Token not object';

        if (is_object($secContext)) $tplData['secContextClass'] = get_class($secContext);
        else                        $tplData['secContextClass'] = 'Sec Context not object';

        $tplData['tokenKey']   = $tokenKey;
        $tplData['tokenCreds'] = $tokenCreds;

        return $this->render('UserBundle:Welcome:index.html.twig',$tplData);
    }
}

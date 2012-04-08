<?php

namespace Zayso\S5GamesBundle\Controller\Account;

use Zayso\CoreBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SigninController extends BaseController
{
    public function signoutAction(Request $request)
    {   
        // So easy once the secret is known, need both
        $this->get('security.context')->setToken(null);
        $request->getSession()->remove('_security_secured_area');
        return $this->redirect($this->generateUrl('zayso_s5games_welcome'));
    }
    public function signinRpxAction(Request $request)
    {
        // Load the profile
        $profile = $this->get('zayso_core.openid.rpx')->getProfile();
        $identifier = $profile['identifier'];
        
        $userProvider = $this->get('zayso_core.user.provider');
        
        try
        {
            $user = $userProvider->loadUserByOpenidIdentifier($identifier);
        }
        catch (UsernameNotFoundException $e)
        {
            $request->getSession()->set('openidProfile',$profile);
            return $this->redirect($this->generateUrl('zayso_s5games_account_create'));
        }
        
        // Continue with normal signin
        $request->getSession()->set(SecurityContext::LAST_USERNAME,$user->getUserName());
        $this->setUser($user);

        // Make sure person is assigned to current project
        $this->getAccountManager()->addProjectPerson($this->getProjectId(),$user->getPersonId());
        
        $browserManager = $this->get('zayso_core.browser.manager');
        $browserManager->add($request->server->get('HTTP_USER_AGENT'));
        
        // Ad off we go
        return $this->redirect($this->generateUrl('zayso_s5games_home'));
    }
}

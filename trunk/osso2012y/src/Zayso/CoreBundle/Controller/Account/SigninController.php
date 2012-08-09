<?php
namespace Zayso\CoreBundle\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

use Symfony\Component\Form\FormError;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

class SigninController extends CoreBaseController
{
    public function checkAction(Request $request)
    {
        $manager = $this->get('zayso_core.account.manager');
        $account = $manager->newAccount();
        
        $signinFormType = $this->get('zayso_core.account.signin.formtype');
        $signinForm = $this->createForm($signinFormType, $account);
        
        if ($request->getMethod() == 'POST') 
        {
            $signinForm->bind($request);

            if ($signinForm->isValid()) 
            {
                $providerKey = $this->container->getParameter('zayso_core.provider.key'); // secured_area
                
                $token = new UsernamePasswordToken($account->getUserName(),$account->getUserPass(),$providerKey,array());
                
                $userAuth = $this->get('zayso_core.user.authentication.provider');
                $tokenx = null;
                try
                {
                    $tokenx = $userAuth->authenticate($token);
                }
                catch (BadCredentialsException $e)
                {
                    $msg = $e->getMessage();
                    $signinForm['userPass']->addError(new FormError($msg));

                }
                catch (AuthenticationException $e)
                {
                    $msg = $e->getMessage();
                    $signinForm['userName']->addError(new FormError($msg));

                }
                if ($tokenx)
                {
                    $this->get('security.context')->setToken($tokenx);
                    return $this->redirect($this->generateUrl('zayso_core_home'));
                }
            }
        }
        $tplData = array();
        $tplData['account'] = $account;
        $tplData['signinForm'] = $signinForm->createView();
        $tplData['janrain_token_route'] = $this->container->getParameter('zayso_core.openid.route');
        
        return $this->renderx('Public:index.html.twig',$tplData);        
    }
    public function signoutAction(Request $request)
    {
        // So easy once the secret is known, need both
        $this->get('security.context')->setToken(null);
        $request->getSession()->remove('_security_secured_area');
        return $this->redirect($this->generateUrl('zayso_core_welcome'));
   }
   public function rpxAction(Request $request)
   {
        // Load the profile
        $profile = $this->get('zayso_core.openid.rpx')->getProfile();
        $identifier = $profile['identifier'];
        if (!$identifier) die('rpx Action no identifier');
        
        $providerKey    = $this->container->getParameter('zayso_core.provider.key'); // secured_area
        
        // Need this for now because of the auth code
        $masterPassword = $this->container->getParameter('zayso_core.user.password');
        
        $token = new UsernamePasswordToken($identifier,$masterPassword,$providerKey,array());
                
        $userAuth = $this->get('zayso_core.user.authentication.provider');
        $tokenx = null;
        try
        {
            $tokenx = $userAuth->authenticate($token);
        }
        catch (AuthenticationException $e)
        {
            $msg = $e->getMessage();
            die('openid . ' . $msg);
        }
        if ($tokenx)
        {
            $this->get('security.context')->setToken($tokenx);
            return $this->redirect($this->generateUrl('zayso_core_home'));
        }
        die($identifier);
   }
}
?>

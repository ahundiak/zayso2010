<?php
namespace Zayso\CoreBundle\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
               
                try
                {
                    $token = $userAuth->authenticate($token);
                }
                catch (AuthenticationException $e)
                {
                    die($e->getMessage());
                }
                $this->get('security.context')->setToken($token);
            }
        }
        $tplData = array();
        $tplData['account'] = $account;
        $tplData['signinForm'] = $signinForm->createView();
        
        return $this->renderx('Public:index.html.twig',$tplData);        
    }
    public function signoutAction(Request $request)
    {
        // So easy once the secret is known, need both
        $this->get('security.context')->setToken(null);
        $request->getSession()->remove('_security_secured_area');
        return $this->redirect($this->generateUrl('zayso_core_welcome'));
   }
}
?>

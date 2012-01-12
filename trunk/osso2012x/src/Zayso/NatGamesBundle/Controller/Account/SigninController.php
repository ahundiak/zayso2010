<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;
use Zayso\CoreBundle\Component\FormValidator\UserNamePassValidator;

use Zayso\CoreBundle\Entity\Account;
use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AccountSigninFormType extends AbstractType
{
    public function getName() { return 'accountSignin'; }
    public function __construct($em) { $this->em = $em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text',     array('label' => 'User Name', 'attr' => array('size' => 35)));
        $builder->add('userPass', 'password', array('label' => 'Password'));

        $builder->add('accountId','hidden', array('property_path' => false));
        $builder->add('memberId', 'hidden', array('property_path' => false));

        $builder->addValidator(new UserNamePassValidator($this->em));
        $builder->get('userPass')->appendClientTransformer(new PasswordTransformer());
    }
}
class SigninController extends BaseController
{
    public function signoutAction(Request $request)
    {   
        // So easy once the secret is known, need both
        $this->get('security.context')->setToken(null);
        $request->getSession()->remove('_security_secured_area');
        return $this->redirect($this->generateUrl('zayso_natgames_welcome'));
    }
    public function signinAction(Request $request)
    {
        $session = $request->getSession();
        $account = new Account();

        // Remember me
        if ($request->getMethod() == 'GET')
        {   
            // get the login error if there is one from login_check
            if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) 
            {
                $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
            } else 
            {
                $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
            }
            // This does not work, no session attributes after signing out
            $account->setUserName($session->get(SecurityContext::LAST_USERNAME));
        }
        // Form
        $formType = new AccountSigninFormType($this->getAccountManager()->getEntityManager());
        $form = $this->createForm($formType, $account);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid()) // Checks username and password
            {
                $userName = $account->getUserName();
                $session->set(SecurityContext::LAST_USERNAME,$userName);
                $this->setUser($userName);
                return $this->redirect($this->generateUrl('zayso_natgames_home'));
            }
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();
        
        return $this->render('ZaysoNatGamesBundle:Account:signin.html.twig',$tplData);
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
            $request->getSession()->setFlash('account_signin_error','Account not found');
            return $this->redirect($this->generateUrl('zayso_natgames_account_signin'));
        }
        // Continue with normal signin
        $request->getSession()->set(SecurityContext::LAST_USERNAME,$user->getUserName());
        $this->setUser($user);

        // Make sure person is assigned to current project
        // This actually gets handled by the home controller
        // $this->getAccountManager()->addProjectPerson($this->getProjectId(),$user->getPersonId());
        
        // Ad off we go
        return $this->redirect($this->generateUrl('zayso_natgames_home'));
    }
}

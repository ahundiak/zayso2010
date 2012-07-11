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

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;

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
        $tplData['account'] = $account;
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
    public function resetAction(Request $request, $id)
    {
        $manager = $this->getAccountManager();
        $accountPerson = $manager->getAccountPerson(array('accountId'=> $id, 'accountRelation' => 'Primary'));
        if (!$accountPerson)
        {
            return $this->redirect($this->generateUrl('zayso_natgames_account_signin'));
        }
        $account = $accountPerson->getAccount();
        $person  = $accountPerson->getPerson();
        if (!$account || !$person || !$person->getEmail())
        {
            return $this->redirect($this->generateUrl('zayso_natgames_account_signin'));
        }
        
        // Generate the reset code
        $reset = md5(uniqid());
        $account->setReset($reset);
        $manager->flush();

        // Build the message
        $subject = sprintf('[NatGames2012] Password Reset Request For %s %s',$person->getFirstName(),$person->getLastName());
        $body    = $this->renderView('ZaysoNatGamesBundle:Account/Reset:email.txt.twig', 
            array('person' => $person, 'reset' => $reset));
        
        // Send email
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom('admin@zayso.org');
        $message->setTo  ($person->getEmail());
        $message->setBcc ('ahundiak@gmail.com');
        $message->setBody($body);

        $this->get('mailer')->send($message);
        
        return $this->render('ZaysoNatGamesBundle:Account/Reset:requested.html.twig',array());
       
        // Show results
        die('Account Reseting ' . $account->getUserName() . ' ' . $person->getEmail() . ' ' . $reset);
    }
    public function resetxAction(Request $request, $reset)
    {
        $manager = $this->getAccountManager();
        $accountPerson = $manager->getAccountPerson(array('reset'=> $reset, 'accountRelation' => 'Primary'));
        if (!$accountPerson)
        {
            return $this->redirect($this->generateUrl('zayso_natgames_account_signin'));
        }
        $account = $accountPerson->getAccount();
        $person  = $accountPerson->getPerson();
        if (!$account || !$person || !$person->getEmail())
        {
            return $this->redirect($this->generateUrl('zayso_natgames_account_signin'));
        }
        $data = array(
            'userName' => $account->getUserName(),
            'userPass1' => null,
            'userPass2' => null,
          //'reset'     => $reset,
        );
        // die('Reset ' . $account->getUserName());
        
        // Form
        $formType = new AccountResetFormType();
        $form = $this->createForm($formType, $data);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid()) // Checks username and password
            {
                $data = $form->getData();
                $account->setUserPass($data['userPass1']);
                $account->setReset(null);
                $manager->flush();
                
                $userName = $account->getUserName();
                $request->getSession()->set(SecurityContext::LAST_USERNAME,$userName);
                $this->setUser($userName);
                return $this->redirect($this->generateUrl('zayso_natgames_home'));
            }
        }
        $tplData = array();
        $tplData['form']  = $form->createView();
        $tplData['reset'] = $reset;
        
        return $this->render('ZaysoNatGamesBundle:Account/Reset:signin.html.twig',$tplData);
    }
}
class AccountResetFormType extends AbstractType
{
    public function getName() { return 'accountReset'; }
  //public function __construct($em) { $this->em = $em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName',  'text',     array('label' => 'User Name', 'read_only' => true));
        $builder->add('userPass1', 'password', array('label' => 'Password', 'required' => false));
        $builder->add('userPass2', 'password', array('label' => 'Password(repeat)'));

        //$builder->add('reset','hidden');

        $builder->get('userPass1')->appendClientTransformer(new PasswordTransformer());
        $builder->get('userPass2')->appendClientTransformer(new PasswordTransformer());
        
        $builder->addValidator(new CallbackValidator(function($form)
        {
            $userPass1 = $form['userPass1']->getData();
            $userPass2 = $form['userPass1']->getData();
            
            if(!$userPass1)
            {
                $form['userPass1']->addError(new FormError('Password Cannot be blank'));
            }
            if($userPass1 != $userPass2)
            {
                $form['userPass2']->addError(new FormError('Passwords do not match'));
            }
       }));

    }
}


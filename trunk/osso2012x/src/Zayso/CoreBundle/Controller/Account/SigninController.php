<?php

namespace Zayso\CoreBundle\Controller\Account;

use Zayso\CoreBundle\Controller\BaseController;

use Zayso\CoreBundle\Entity\Account;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class SigninController extends BaseController
{
    public function signoutAction(Request $request)
    {   
        // So easy once the secret is known, need both
        $this->get('security.context')->setToken(null);
        $request->getSession()->remove('_security_secured_area');
        return $this->redirect($this->generateUrl('zayso_core_welcome'));
    }
    public function signinAction(Request $request)
    {
        $account = new Account();
        $account->setUserName($request->getSession()->get(SecurityContext::LAST_USERNAME));

        // Forms
        $signinFormType = $this->get('zayso_core.account.signin.formtype');
        $signinForm = $this->createForm($signinFormType, $account);
        
        $resetPasswordFormType = $this->get('zayso_core.account.reset.password.formtype');
        $resetPasswordForm     = $this->createForm($resetPasswordFormType, $account);

        if ($request->getMethod() == 'POST')
        {
            if ($request->request->get('signin_submit'))
            {
                $signinForm->bindRequest($request);

                if ($signinForm->isValid()) // Checks username and password
                {
                    $userName = $account->getUserName();
                    $request->getSession()->set(SecurityContext::LAST_USERNAME,$userName);
                    $this->setUser($userName);
                    
                    $request->getSession()->set('mySchSearchData2012', null);
                    $request->getSession()->set('refSchSearchData2012',null);
 
                    return $this->redirect($this->generateUrl('zayso_core_home'));
                }
            }
            if ($request->request->get('password_reset_submit'))
            {
                $resetPasswordForm->bindRequest($request);

                if ($resetPasswordForm->isValid()) // Checks username
                {
                    return $this->passwordResetRequest($account->getId());
                }
            }
        }
        $tplData = array();
        $tplData['signinForm']        = $signinForm->createView();
        $tplData['resetPasswordForm'] = $resetPasswordForm->createView();
        
        return $this->renderx('ZaysoCoreBundle:Account/Signin:help.html.twig',$tplData);
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
            // Assume they want to create a new account
            $request->getSession()->set('openidProfile',$profile);
            return $this->redirect($this->generateUrl('zayso_core_account_create'));
            
            // Or maybe an actual error?
            $request->getSession()->setFlash('account_signin_error','Account not found');
            return $this->redirect($this->generateUrl('zayso_core_account_signin'));
        }
        // Continue with normal signin
        $request->getSession()->set(SecurityContext::LAST_USERNAME,$user->getUserName());
        $this->setUser($user);

        // Make sure person is assigned to current project
        // This actually gets handled by the home controller
        // $this->getAccountManager()->addProjectPerson($this->getProjectId(),$user->getPersonId());
        
                $request->getSession()->set('mySchSearchData2012', null);
                $request->getSession()->set('refSchSearchData2012',null);
         
        // Ad off we go
        return $this->redirect($this->generateUrl('zayso_core_home'));
    }
    /* ======================================================
     * Should have a valid account id here
     */
    public function passwordResetRequest($accountId)
    {
        $manager = $this->get('zayso_core.account.home.manager');
        
        $accountPerson = $manager->loadPrimaryAccountPersonForAccount($accountId);
        
        if (!$accountPerson)
        {
            return $this->redirect($this->generateUrl('zayso_core_account_signin'));
        }
        
        $account = $accountPerson->getAccount();
        $person  = $accountPerson->getPerson();
        if (!$account || !$person || !$person->getEmail())
        {
            return $this->redirect($this->generateUrl('zayso_core_account_signin'));
        }
        
        // Generate the reset code
        $reset = md5(uniqid());
        $account->setReset($reset);
        $manager->flush();
        
        // Build the message
        $subject = sprintf('[%s] Password Reset Request For %s %s',
                $this->getMyTitlePrefix(),
                $person->getFirstName(),$person->getLastName());
        
        $body = $this->renderView('ZaysoCoreBundle:Account/PasswordReset:email.txt.twig', 
            array(
                'person' => $person, 
                'reset'  => $reset,
              //'myBundleName'  => $this->getMyBundleName(),
              //'myTitlePrefix' => $this->getMyTitlePrefix(),
           ));
        
        // Send email
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom('admin@zayso.org');
        $message->setTo  ($person->getEmail());
        $message->setBcc ('ahundiak@gmail.com');
        $message->setBody($body);

        $this->get('mailer')->send($message);
        
        return $this->renderx('ZaysoCoreBundle:Account/PasswordReset:requested.html.twig',array());
       
        // Show results
        die('Account Reseting ' . $account->getUserName() . ' ' . $person->getEmail() . ' ' . $reset);
    }
    public function passwordResetAction(Request $request, $reset)
    {
        $manager = $this->get('zayso_core.account.home.manager');
        
        $account = $manager->loadAccountForReset($reset);
        
        if (!$account)
        {
            return $this->redirect($this->generateUrl('zayso_core_account_signin'));
        }
        $this->setUser($account->getUserName());
        
        $account->setReset(null);
        $manager->flush();
        
        return $this->redirect($this->generateUrl('zayso_core_account_edit'));
    }
}



<?php

namespace Zayso\AreaBundle\Controller\Account;

use Zayso\AreaBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormError;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LegacySigninFormType extends AbstractType
{
    public function getName() { return 'accountSignin'; }
    public function __construct($em) { $this->em = $em; }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('userName', 'text',     array('label' => 'User Name', 'attr' => array('size' => 35)));
        $builder->add('userPass', 'password', array('label' => 'Password'));

        $builder->get('userPass')->appendClientTransformer(new PasswordTransformer());
    }
}
class LegacyController extends BaseController
{
    /* ===============================================================
     * Have validated 2012 account
     * Link openid
     * Set proejct
     * Set user
     */
    protected function processAccount($account2012,$profile)
    {
        $accountManager = $this->getAccountManager();
        
        // Openid
        $result = $accountManager->linkAccountToOpenid($account2012,$profile);
        if (!is_object($result)) return $result;
        
        // It's possible might have multiple account persons, link all to project for now
        foreach($account2012->getMembers() as $accountPerson)
        {
            // Add project person
            $accountManager->addProjectPerson($this->getProjectId(),$accountPerson->getPerson());
        }
        // User
        $userProvider = $this->get('zayso_core.user.provider');
        
        try
        {
            $user = $userProvider->loadUserByUsername($account2012->getUserName());
        }
        catch (UsernameNotFoundException $e)
        {
            // Really should not get here, might want to send an email
            $request->getSession()->set('openidProfile',$profile); // die('openid identifier not found');
            return $this->redirect($this->generateUrl('zayso_area_account_legacy_signin'));
        }
        
         // Continue with normal signin
        $this->request->getSession()->set(SecurityContext::LAST_USERNAME,$user->getUserName());
        $this->setUser($user);
        
        // Tell the master
        $this->sendEmail('2007 Account Processed','The Body');
        
        // And done
        return $this->redirect($this->generateUrl('zayso_area_home'));
    }
    /* ===============================================================
     * At this pointthe user has selected an opein account and has
     * entered a possible zayso user name and password
     * 
     * Lots of messy things to check here
     */
    protected function process($userName,$userPass,$profile)
    {   
        // It is possible that have an unlinked osso2012 account
        $accountManager2012 = $this->getAccountManager();
        $account2012 = $accountManager2012->loadAccountForUserName($userName);
        if ($account2012)
        {
            // Check password
            if ($userPass != $account2012->getUserPass())
            {
                if ($userPass != $accountManager2012->getMasterPassword())
                {
                    return 'Account exists in 2012 but password is incorrect';
                }
            }
            // Use the existing account
            return $this->processAccount($account2012,$profile);
        }
        // See if have a fully functional account in 2007
        $accountManagerLegacy = $this->get('zayso_area.account.manager.legacy');
        $account2007 = $accountManagerLegacy->checkAccount2007($userName,$userPass);
        if (!is_object($account2007)) return $account2007;
        
        // Import the account
        $account2012 = $accountManagerLegacy->copyAccount2007($account2007);
        
        // And use it
        return $this->processAccount($account2012,$profile);
    }
    /* ===============================================================
     * In theory the user has selected an openid account but it is
     * not currently tied to any existing zayso account
     * 
     * So they either pack an account or create a new one
     */
    public function signinAction(Request $request)
    {
        $this->request = $request;
        
        /* ==============================================================
         * Profile should be in the session
         */
        $profile = $request->getSession()->get('openidProfile');
        if (!$this->isProfileValid($profile))
        {
            return $this->redirect($this->generateUrl('zayso_area_welcome'));
        }

        // Sign in form
        $accountManager = $this->getAccountManager();
        $account = $accountManager->newAccountEntity();

        $account->setUserName($request->getSession()->get(SecurityContext::LAST_USERNAME));
        
        $formType = new LegacySigninFormType($accountManager->getEntityManager());
        $form = $this->createForm($formType, $account);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);
            
            if ($form->isValid()) 
            {
                $userName = $account->getUserName();
                $userPass = $account->getUserPass();

                $request->getSession()->set(SecurityContext::LAST_USERNAME,$userName);

                $result = $this->process($userName,$userPass,$profile);
                if (is_object($result)) return $result;
                
                $form->addError(new FormError($result));
            }
        }
        // And render
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['displayName'] = $profile['displayName'];
        $tplData['provider']    = $profile['providerName'];

        return $this->render('ZaysoAreaBundle:Account:legacy.html.twig',$tplData);
    }
    /* ===============================================================
     * This is the main entry point
     * The user clicked an openid icon which is not currently linked to
     * a zayso2012 account
     *
     * Check to see if a 2007 account uses the openid
     * If so copy the account and done
     *
     * If not then redirect and present a signin form
     */
    public function profileAction(Request $request)
    {
        // For later use
        $this->request = $request;

        // Openid profile
        $profile = $request->getSession()->get('openidProfile');
        if (!$this->isProfileValid($profile))
        {
            return $this->redirect($this->generateUrl('zayso_area_welcome'));
        }
        $identifier = $profile['identifier'];

        // See if in 2007
        $accountManagerLegacy = $this->get('zayso_area.account.manager.legacy');
        $account2007 = $accountManagerLegacy->checkOpenid2007($identifier);
        if (!is_object($account2007))
        {
            return $this->redirect($this->generateUrl('zayso_area_account_legacy_signin'));
        }
        // Import the account
        $account2012 = $accountManagerLegacy->copyAccount2007($account2007);

        // And use it
        return $this->processAccount($account2012,$profile);
    }
    /* ===============================================
     * Use this check because sometime the session variable
     * seems to go away
     */
    protected function isProfileValid($profile)
    {
        if (!is_array($profile)) return false;

        // Must have identifier
        if (!isset($profile['identifier']) || (strlen($profile['identifier']) < 8)) return false;

        // Must have providername
        if (!isset($profile['providerName']) || (strlen($profile['providerName']) < 4)) return false;

        // Good enough
        return true;
    }

 }

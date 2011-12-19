<?php

namespace Zayso\ZaysoAreaBundle\Controller\Account;

use Zayso\ZaysoAreaBundle\Controller\BaseController;

use Zayso\ZaysoCoreBundle\Component\DataTransformer\PasswordTransformer;

use Zayso\ZaysoCoreBundle\Component\Debug;

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
        $account2012Manager = $this->get('zayso_area.account.manager');
        
        // Openid
        $result = $account2012Manager->linkAccountToOpenid($account2012,$profile);
        if (!is_object($result)) return $result;
        
        // It's possible might have multiple account persons, link all to project for now
        foreach($account2012->getMembers() as $accountPerson)
        {
            // Add project person
            $account2012Manager->addProjectPerson($this->getProjectId(),$accountPerson->getPerson());
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
            return $this->redirect($this->generateUrl('zayso_area_account_legacy_profile'));
        }
        
         // Continue with normal signin
        $this->request->getSession()->set(SecurityContext::LAST_USERNAME,$user->getUserName());
        $this->setUser($user);
        
        // Tell the master
        $this->sendEmail('2007 Account Processed','The Body');
        
        // And done
        return $this->redirect($this->generateUrl('zayso_area_welcome'));
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
        $account2012Manager = $this->get('zayso_area.account.manager');
        $account2012 = $account2012Manager->loadAccountForUserName($userName);
        if ($account2012)
        {
            // Check password
            if ($userPass != $account2012->getUserPass())
            {
                if ($userPass != $account2012Manager->getMasterPassword())
                {
                    return 'Account exists in 2012 but password is incorrect';
                }
            }
            // Use the existing account
            return $this->processAccount($account2012,$profile);
        }
        // See if have a fully functional account in 2007
        $account2007Manager = $this->get('zayso_area.account.manager2007');
        $account2007 = $account2007Manager->checkAccount2007($userName,$userPass);
        if (!is_object($account2007)) return $account2007;
        
        // Import the account
        $account2012 = $account2007Manager->copyAccount2007($account2007);
        
        // And use it
        return $this->processAccount($account2012,$profile);
    }
    /* ===============================================================
     * In theory the user has selected an openid account but it is
     * not currently tied to any existing zayso account
     * 
     * So they either pack an account or create a new one
     */
    public function profileAction(Request $request)
    {
        $this->request = $request;
        
        /* ==============================================================
         * Profile should be in the session
         * During development sometimes the info seems to have gotten corrupted?
         * Hence the strlen nonsense
         */
        $profile = $request->getSession()->get('openidProfile');
        if (isset($profile['providerName'])) $provider = $profile['providerName'];
        else                                 $provider = null;
        if (strlen($provider) < 5)
        {
            return $this->redirect($this->generateUrl('zayso_area_welcome'));
        }
        
        // Sign in form
        $accountManager = $this->getAccountManager();
        $account = $accountManager->newAccountEntity();
        
        $formType = new LegacySigninFormType($accountManager->getEntityManager());
        $form = $this->createForm($formType, $account);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);
            
            if ($form->isValid()) 
            {
                $userName = $account->getUserName();
                $userPass = $account->getUserPass();
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

        return $this->render('ZaysoAreaBundle:Account:openid.html.twig',$tplData);
    }
 }

<?php
namespace Zayso\CoreBundle\Controller\Account;

use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class CreateController extends BaseController
{
    public function createAction(Request $request)
    {
        // Must have a profile to get here
        $profile = $request->getSession()->get('openidProfile');
        if (!$this->isProfileValid($profile)) $profile = null;
        if (!$profile)
        {
            // Allow creating without a profile
            //return $this->redirect($this->generateUrl('zayso_core_welcome'));
        }
        // New form stuff
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->newAccountPersonAyso();

        // Init with profile info
        if ($profile)
        {
            $accountPerson->setOpenidDisplayName($profile['displayName']);
            $accountPerson->setOpenidProvider   ($profile['providerName']);
        
            // Pretty much confirmed that we always get one of these
            if (isset($profile['preferredUsername'])) 
            {
                $accountPerson->setOpenidUserName($profile['preferredUsername']);
                $accountPerson->setUserName      ($profile['preferredUsername']);
            }
            // Not for openid
            if (isset($profile['email']))             $accountPerson->setEmail($profile['email']);
            if (isset($profile['verifiedEmail']))     $accountPerson->setEmail($profile['verifiedEmail']);
            
            if (isset($profile['name']['givenName' ])) $accountPerson->setFirstName($profile['name']['givenName']);
            if (isset($profile['name']['familyName'])) $accountPerson->setLastName ($profile['name']['familyName']);
        }
        
        // Not real sure about this
        if (!$accountPerson->getUserName())
        {
            // $accountPerson->setUserName(md5(uniqid()));
        }
        $accountPerson->setUserPass(md5(uniqid()));
        
        
        // The form
        $accountFormType = $this->get('zayso_core.account.create.formtype');

        $form = $this->createForm($accountFormType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid() && 1)
            {
                $accountPerson->setProjectPersonData($this->getProjectId());
                if ($profile)
                {
                    $openid = $accountPerson->getFirstOpenid();
                    $openid->setProfile($profile);
                }
                $account = $accountManager->createAccountFromAccountPersonAyso($accountPerson);
                
                if ($account) 
                {                    
                    // Sign in
                    $user = $this->setUser($account->getUserName());
                    
                    // Send email
                    $subject = sprintf('[%s] - Created %s %s %s',
                            $this->getMyBundleName(),
                            $user->getName(),$user->getRegion(),$user->getAysoid());
                    $this->sendEmail($subject,$subject);
                    
                    return $this->redirect($this->generateUrl('zayso_core_home'));
                }
            }
        }
        $tplData = array();
        $tplData['form'] = $form->createView();

        return $this->renderx('ZaysoCoreBundle:Account:create.html.twig',$tplData);
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

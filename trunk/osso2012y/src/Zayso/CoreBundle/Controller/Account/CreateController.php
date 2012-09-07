<?php
namespace Zayso\CoreBundle\Controller\Account;

use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class CreateController extends BaseController
{
    public function createAction(Request $request)
    {
        // Usually have a profile
        $profile = $request->getSession()->get('openidProfile');
        if (!$this->isProfileValid($profile)) $profile = null;

        // New form stuff
        $manager = $this->get('zayso_core.account.manager');
        
        $account = $manager->newAccount();

        if ($profile)
        {
            $accountPerson->setOpenidDisplayName($profile['displayName']);
            $accountPerson->setOpenidProvider   ($profile['providerName']);

            if (isset($profile['preferredUsername'])) $accountPerson->setUserName($profile['preferredUsername']);
            if (isset($profile['email']))             $accountPerson->setEmail($profile['email']);
            if (isset($profile['verifiedEmail']))     $accountPerson->setEmail($profile['verifiedEmail']);
            
            if (isset($profile['name']['givenName' ])) $accountPerson->setFirstName($profile['name']['givenName']);
            if (isset($profile['name']['familyName'])) $accountPerson->setLastName ($profile['name']['familyName']);
        }
        $formType = $this->get('zayso_core.account.create.formtype');
        
        $form = $this->createForm($formType, $account);

        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if ($form->isValid())
            {
                $manager->createAccount($account);
                
                // Sign in
                $user = $this->setUser($account->getUserName());
                    
                return $this->redirect($this->generateUrl('zayso_core_home'));
                 
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

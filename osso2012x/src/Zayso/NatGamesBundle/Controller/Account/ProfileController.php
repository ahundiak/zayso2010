<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    protected function getContactForm($accountPerson)
    {
        $formType = $this->get('zayso_natgames.account.profile.contact.formtype');
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    protected function getPasswordForm($accountPerson)
    {
        $formType = $this->get('zayso_natgames.account.profile.password.formtype');
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    protected function getAysoForm($accountPerson)
    {
        $formType = $this->get('zayso_natgames.account.profile.ayso.formtype');
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    protected function getTplDatax($accountPerson)
    {
        $tplData = $this->getTplData();

        $tplData['helper'] = $this;
        
        $tplData['account'] = $accountPerson->getAccount();
        
        $tplData['contactForm'] = $this->getContactForm($accountPerson)->createView();
        $tplData['contactFlash']= $this->getSession()->getFlash('accountProfileContactUpdated');

        $tplData['passwordForm'] = $this->getPasswordForm($accountPerson)->createView();
        $tplData['passwordFlash']= $this->getSession()->getFlash('accountProfilePasswordUpdated');

        $tplData['aysoForm'] = $this->getAysoForm($accountPerson)->createView();
                
        $formType = $this->get('zayso_natgames.account.person.list.formtype');
        $form     = $this->createForm($formType,$accountPerson->getAccount());
        $tplData['accountPersonListForm'] = $form->createView();
        
        $tplData['openids'] = $this->getAccountManager()->getOpenidsForAccount($this->getUser()->getAccountId());
        
        return $tplData;
    }
    public function indexAction()
    {
        $user = $this->getUser();
        $accountManager = $this->getAccountManager();
        $params = array('accountPersonId' => $user->getAccountPersonId());
        $accountPerson = $accountManager->getAccountPerson($params);

        // Render
        $tplData = $this->getTplDatax($accountPerson);
 
        return $this->render('ZaysoNatGamesBundle:Account:profile.html.twig',$tplData);
    }
    public function postAction(Request $request)
    {
        $submit = $request->get('accountProfileContactSubmit');
        if ($submit) return $this->postContactAction($request);

        $submit = $request->get('accountProfilePasswordSubmit');
        if ($submit) return $this->postPasswordAction($request);

        return $this->redirect($this->generateUrl('zayso_natgames_account_profile'));
        
    }
    public function postContactAction($request)
    {
        // Pull user
        $postedData = $request->get('accountProfileContact'); // Just an array
        $accountPersonId = $postedData['accountPersonId'];
        
        // Get the current id
        // Really just a double check
        $user = $this->getUser();
        if ($accountPersonId != $user->getAccountPersonId()) return $this->redirect($this->generateUrl('zayso_natgames_welcome'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId));
        if (!$accountPerson) return $this->redirect($this->generateUrl('zayso_natgames_welcome'));

        // Validate
        $form = $this->getContactForm($accountPerson);
        $form->bindRequest($request);
        
        if ($form->isValid())
        {
            // Store results
            $accountManager->getEntityManager()->flush();
            $request->getSession()->setFlash('accountProfileContactUpdated','Profile has been updated.');
            
            // Need to update user with possible changes
            $this->setUser($accountPerson->getUserName());
            
            // Be better to refresh entire user?
            //$user->setFirstName($accountPerson->getFirstName());
            //$user->setLastName ($accountPerson->getLastName());
            //$user->setNickName ($accountPerson->getNickName());
            
            // Redirect
            return $this->redirect($this->generateUrl('zayso_natgames_account_profile'));
        }
        // Render Errors
        $tplData = $this->getTplDatax($accountPerson);
        
        $tplData['contactForm']  = $form->createView();

        return $this->render('ZaysoNatGamesBundle:Account:profile.html.twig',$tplData);

    }
    public function postPasswordAction($request)
    {
        // Pull user
        $postedData = $request->get('accountProfilePassword');
        $accountPersonId = $postedData['accountPersonId'];

        // Get the current id
        $user = $this->getUser();
        if ($accountPersonId != $user->getAccountPersonId()) return $this->redirect($this->generateUrl('zayso_natgames_welcomex'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId));
        if (!$accountPerson) return $this->redirect($this->generateUrl('zayso_natgames_welcomex'));

        // Validate
        $form = $this->getPasswordForm($accountPerson);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            // Save the changes
            $accountManager->getEntityManager()->flush();
            $this->getSession()->setFlash('accountProfilePasswordUpdated','User Name or Password has been updated.');
            
            // Update the user
            $this->setUser($accountPerson->getUserName());
            
            // Done
            return $this->redirect($this->generateUrl('zayso_natgames_account_profile'));
        }
        // Render Errors
        $tplData = $this->getTplDatax($accountPerson);

        $tplData['passwordForm'] = $form->createView();
        
        return $this->render('ZaysoNatGamesBundle:Account:profile.html.twig',$tplData);
    }
    /* ==================================================================
     * Try using a call back for this
     * {% render "NatGamesBundle:Account/Profile:renderEaysoInfo" with {'accountPerson': accountPerson} %}
     *
     * Very verbose and needs to have the controller be in a different file
     */
    public function renderEaysoInfoAction($accountPerson)
    {
        return 'eayso info';
    }
    public function renderEaysoInfo($accountPerson)
    {
        $aysoid = substr($accountPerson->getAysoid(),4);
        $region = substr($accountPerson->getRegion(),4);

        $refBadge  = $accountPerson->getRefBadge();
        if ($refBadge == 'None')
        {
            $refBadge = '<span style="background: yellow;">' . $refBadge . '</span>';
        }
        $safeHaven = $accountPerson->getSafeHaven();
        if ($safeHaven == 'None')
        {
            $safeHaven = '<span style="background: yellow;">' . $safeHaven . '</span>';
        }

        $memYear = 'MY' . $accountPerson->getMemYear();
        if ($memYear < 'MY2011')
        {
            $memYear = '<span style="background: yellow;">' . $memYear . '</span>';
        }

        $html  =  $aysoid . ' ' . $memYear  . ' ' . $region . '<br />';
        $html .= 'Ref Badge: '  . $refBadge . '<br />' ;
        $html .= 'Safe Haven: ' . $safeHaven;

        return $html;
    }
}

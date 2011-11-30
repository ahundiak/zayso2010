<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    protected function getContactForm($accountPerson)
    {
        $formType = $this->get('account.profile.contact.formtype');
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    protected function getPasswordForm($accountPerson)
    {
        $formType = $this->get('account.profile.password.formtype');
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    protected function getAysoForm($accountPerson)
    {
        $formType = $this->get('account.profile.ayso.formtype');
        $form     = $this->createForm($formType, $accountPerson);
        return $form;
    }
    protected function getTplDatax($accountPerson)
    {
        $tplData = $this->getTplData();
        
        $tplData['account']     = $accountPerson->getAccount();
        
        $tplData['contactForm'] = $this->getContactForm($accountPerson)->createView();
        $tplData['contactFlash']= $this->getSession()->getFlash('accountProfileContactUpdated');

        $tplData['passwordForm'] = $this->getPasswordForm($accountPerson)->createView();
        $tplData['passwordFlash']= $this->getSession()->getFlash('accountProfilePasswordUpdated');

        $tplData['aysoForm'] = $this->getAysoForm($accountPerson)->createView();
                
        $formType = $this->get('account.person.list.formtype');
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
 
        return $this->render('NatGamesBundle:Account:profile.html.twig',$tplData);
    }
    public function postAction(Request $request)
    {
        $submit = $request->get('accountProfileContactSubmit');
        if ($submit) return $this->postContactAction($request);

        $submit = $request->get('accountProfilePasswordSubmit');
        if ($submit) return $this->postPasswordAction($request);

        return $this->redirect($this->generateUrl('natgames_account_profile'));
        
    }
    public function postContactAction($request)
    {
        // Pull user
        $postedData = $request->get('accountProfileContact'); // Just an array
        $accountPersonId = $postedData['accountPersonId'];
        
        // Get the current id
        // Really just a double check
        $user = $this->getUser();
        if ($accountPersonId != $user->getAccountPersonId()) return $this->redirect($this->generateUrl('natgames_welcome'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId));
        if (!$accountPerson) return $this->redirect($this->generateUrl('natgames_welcome'));

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
            return $this->redirect($this->generateUrl('natgames_account_profile'));
        }
        // Render Errors
        $tplData = $this->getTplDatax($accountPerson);
        
        $tplData['contactForm']  = $form->createView();

        return $this->render('NatGamesBundle:Account:profile.html.twig',$tplData);

    }
    public function postPasswordAction($request)
    {
        // Pull user
        $postedData = $request->get('accountProfilePassword');
        $accountPersonId = $postedData['accountPersonId'];

        // Get the current id
        $user = $this->getUser();
        if ($accountPersonId != $user->getAccountPersonId()) return $this->redirect($this->generateUrl('natgames_welcome'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId));
        if (!$accountPerson) return $this->redirect($this->generateUrl('natgames_welcome'));

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
            return $this->redirect($this->generateUrl('natgames_account_profile'));
        }
        // Render Errors
        $tplData = $this->getTplDatax($accountPerson);

        $tplData['passwordForm'] = $form->createView();
        
        return $this->render('NatGamesBundle:Account:profile.html.twig',$tplData);
    }
}

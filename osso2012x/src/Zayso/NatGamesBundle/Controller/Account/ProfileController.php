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
        
        $tplData['id']          = $accountPerson->getId();
        $tplData['account']     = $accountPerson->getAccount();
        
        $tplData['contactForm'] = $this->getContactForm($accountPerson)->createView();
        $tplData['contactFlash']= $this->getSession()->getFlash('accountProfileContactUpdated');

        $tplData['passwordForm'] = $this->getPasswordForm($accountPerson)->createView();
        $tplData['passwordFlash']= $this->getSession()->getFlash('accountProfilePasswordUpdated');

        $tplData['aysoForm'] = $this->getAysoForm($accountPerson)->createView();
                
        $formType = $this->get('account.person.list.formtype');
        $form     = $this->createForm($formType,$accountPerson->getAccount());
        $tplData['accountPersonListForm'] = $form->createView();
        
        return $tplData;
    }
    public function indexAction()
    {
        // Must be signed in
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $accountPerson = $user->getAccountPerson();

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

        return $this->redirect($this->generateUrl('_natgames_account_profile'));
        
    }
    public function postContactAction($request)
    {
        // Pull user
        $postedData = $request->get('accountProfileContact'); // Just an array
        $accountPersonId = $postedData['accountPersonId'];

        // Authorize
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));
        
        // Get the current id
        if ($accountPersonId != $user->getAccountPersonId()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId, 'projectId' => $this->getProjectId()));
        if (!$accountPerson) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Validate
        $form = $this->getContactForm($accountPerson);
        $form->bindRequest($request);
        
        if ($form->isValid())
        {
            $accountManager->getEntityManager()->flush();
            $this->getSession()->setFlash('accountProfileContactUpdated','Profile has been updated.');
            return $this->redirect($this->generateUrl('_natgames_account_profile'));
        }
        // Render Errors
        $tplData = $this->getTplDatax($accountPerson);
        
        $tplData['id']           = $accountPersonId;
        $tplData['contactForm']  = $form->createView();

        return $this->render('NatGamesBundle:Account:profile.html.twig',$tplData);

        //Debug::dump($postedData);
        //die('postContactAction');
        //$accountPersonId =
    }
    public function postPasswordAction($request)
    {
        // Pull user
        $postedData = $request->get('accountProfilePassword');
        $accountPersonId = $postedData['accountPersonId'];

        // Authorize
        $user = $this->getUser();
        if (!$user->isSignedIn()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the current id
        if ($accountPersonId != $user->getAccountPersonId()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Get the account
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $accountPersonId, 'projectId' => $this->getProjectId()));
        if (!$accountPerson) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        // Validate
        $form = $this->getPasswordForm($accountPerson);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            $accountManager->getEntityManager()->flush();
            $this->getSession()->setFlash('accountProfilePasswordUpdated','User Name or Password has been updated.');
            return $this->redirect($this->generateUrl('_natgames_account_profile'));
        }
        // Render Errors
        $tplData = $this->getTplDatax($accountPerson);

        $tplData['passwordForm'] = $form->createView();
        
        return $this->render('NatGamesBundle:Account:profile.html.twig',$tplData);

        //Debug::dump($postedData);
        //die('postContactAction');
        //$accountPersonId =
    }
}

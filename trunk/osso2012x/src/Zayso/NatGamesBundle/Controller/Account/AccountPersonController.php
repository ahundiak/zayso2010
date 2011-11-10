<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class AccountPersonController extends BaseController
{
    public function addAction(Request $request)
    {   
        // New form stuff
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->newAccountPerson(array('projectId' => $this->getProjectId()));
        $accountPerson->setAccountRelation('Family');
        
        $formType = $this->get('account.person.add.formtype');

        $form = $this->createForm($formType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $accountPerson = $this->addAccountPerson($accountPerson);
                
                //if ($accountPerson) return $this->redirect($this->generateUrl('_natgames_account_profile'));
                
            }
            // else die('Not validated');
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Account\Person:add.html.twig',$tplData);
    }
    public function addAccountPerson($accountPerson)
    {   
        $accountManager = $this->getAccountManager();
        $em = $accountManager->getEntityManager();

        // Set to active account
        $account = $this->getUser()->getAccount();
        $accountPerson->setAccount($account);

        // Replace person if exists
        $person = $accountManager->getPerson(array('projectId' => $this->getProjectId(),'aysoid' => $accountPerson->getAysoid()));
        if ($person)
        {
            // Need to ensure same person is not attached to account more than once
            if ($account->getPersonForId($person->getId())) return $accountPerson;

            // Use it
            $accountPerson->setPerson($person);
        }
        // Should be try/catch
        $em->persist($accountPerson);
        $em->flush();

        return $accountPerson;
  }
}

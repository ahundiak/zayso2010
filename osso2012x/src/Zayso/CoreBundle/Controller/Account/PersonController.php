<?php

namespace Zayso\CoreBundle\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

/* ===========================================================
 * NatGames
 */
class PersonController extends BaseController
{
    public function editAction(Request $request, $id = 0)
    {
        $manager = $this->get('zayso_core.account.home.manager');
        
        // Load the account person
        if ($id) $accountPersonId = $id;
        else     $accountPersonId = $this->getUser()->getAccountPersonId();
        
        $accountPerson = $manager->loadAccountPerson($accountPersonId);
        
        if (!$accountPerson || $accountPerson->getAccount()->getId() != $this->getUser()->getAccountId())
        {
            return $this->redirect($this->generateUrl('zayso_core_home'));
        }
        $formType = $this->get('zayso_core.account.person.edit.formtype');
        
        $form = $this->createForm($formType, $accountPerson);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {   
                $manager->flush();
                
                // If Primary then reload user infomation
                if ($accountPerson->isPrimary())
                {
                    $this->setUser($accountPerson->getUserName());
                }
                   
                // Security check   
                $subject = sprintf('[%s][Account] - Edited Person %s %s',
                    $this->getMyTitlePrefix(),
                    $accountPerson->getUserName(),
                    $accountPerson->getPersonName()
                );
                    
                $this->sendEmail($subject,$subject);
                
                return $this->redirect($this->generateUrl('zayso_core_home'));
            }
        }
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['accountPerson'] = $accountPerson;
        
        return $this->renderx('Account\Person:edit.html.twig',$tplData);
        
    }
    public function addAction(Request $request)
    {
        $manager = $this->get('zayso_core.account.manager');
        
        $accountId = $this->getUser()->getAccountId();
        
        $accountPerson = $manager->newAccountPersonAyso();
        $accountPerson->setAccountRelation('Family');
        
        $accountFormType = $this->get('zayso_core.account.person.add.formtype');
        
        $form = $this->createForm($accountFormType, $accountPerson);
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {   
                $accountPerson->setProjectPersonData($this->getProjectId());
                
                $account = $manager->getAccountReference($accountId);
                $accountPerson->setAccount($account);
                
                $addedAccountPerson = $manager->addAccountPersonAyso($accountPerson);
                
                if (is_object($addedAccountPerson)) 
                {
                    $primaryAccountPerson = $manager->loadPrimaryAccountPerson($accountId);
                    
                    // Security check   
                    $subject = sprintf('[%s][Account] - Added Person %s %s TO %s',
                        $this->getMyTitlePrefix(),
                        $addedAccountPerson->getAccountRelation(),
                        $addedAccountPerson->getPersonName(),
                        $primaryAccountPerson->getPersonName());
                    
                    $this->sendEmail($subject,$subject);
                    
                    return $this->redirect($this->generateUrl('zayso_core_home'));
                }
            }
        }
        $tplData = array();
        $tplData['form'] = $form->createView();

        return $this->renderx('Account\Person:add.html.twig',$tplData);
    }
}
?>

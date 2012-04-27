<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

class PersonController extends BaseController
{
    public function editAction(Request $request, $id = 0)
    {
        return $this->redirect($this->generateUrl('zayso_natgames_home'));
    }
    public function addAction(Request $request)
    {
        $manager = $this->get('zayso_core.account.manager');
        
        $accountId = $this->getUser()->getAccountId();
        
        $accountPerson = $manager->newAccountPersonAyso();
        $accountPerson->setAccountRelation('Family');
        
        $accountFormType = $this->get('zaysocore.account.person.add.formtype');
        
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
                
                if (is_object($accountPerson)) 
                {
                    $primaryAccountPerson = $manager->loadPrimaryAccountPerson($accountId);
                    
                    // Security check   
                    $subject = sprintf('[NatGames] - Added %s %s TO %s',
                        $addedAccountPerson->getAccountRelation(),
                        $addedAccountPerson->getPersonName(),
                        $primaryAccountPerson->getPersonName());
                    
                    $this->sendEmail($subject,$subject);
                    
                    return $this->redirect($this->generateUrl('zayso_natgames_home'));
                }
            }
        }
        $tplData = array();
        $tplData['form'] = $form->createView();

        return $this->render('ZaysoNatGamesBundle:Account\Person:add.html.twig',$tplData);
    }
}
?>

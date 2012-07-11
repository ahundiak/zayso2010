<?php

namespace Zayso\NatGamesBundle\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;
use Zayso\CoreBundle\Controller\Account\PersonController as AccountPersonBaseController;

class PersonController extends AccountPersonBaseController
{
    // Obsolete
    public function editAccountAction(Request $request, $id = 0)
    {
        $manager = $this->get('zayso_core.account.home.manager');
        
        // Only the primary account holder can use this or administrator?
        $accountPersonId = $this->getUser()->getAccountPersonId();
        
        $accountPerson = $manager->loadAccountPerson($accountPersonId);
        if (!$accountPerson)
        {
           return $this->redirect($this->generateUrl('zayso_natgames_home'));            
        }
        $account = $accountPerson->getAccount();
        
        $formType = $this->get('zaysocore.account.edit.formtype');
        
        $form = $this->createForm($formType, $account);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $userName = $account->getUserName();
                $userPass = $account->getUserPass();
                
                $manager->refresh($account);
                
                $needFlush = false;
                
                if ($userName != $account->getUserName())
                {
                    $account->setUserName($userName);
                    $needFlush = true;
                }
                if ($userPass && $userPass != $account->getUserPass())
                {
                    $account->setUserPass($userPass);
                    $needFlush = true;
                }
                if ($needFlush) 
                {
                    $manager->flush();               
                }
                return $this->redirect($this->generateUrl('zayso_natgames_home'));
               
                // Maybe send an email for jolly jokers
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
        $tplData['accountPerson'] = $accountPerson;
        
        return $this->render('ZaysoNatGamesBundle:Account\Person:account.html.twig',$tplData);
        
    }
}
?>

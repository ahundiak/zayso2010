<?php

namespace Zayso\CoreBundle\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

/* ========================================================
 * NatGames
 */
class EditController extends BaseController
{
    public function editAction(Request $request, $id = 0)
    {
        $manager = $this->get('zayso_core.account.home.manager');
        
        // Only the primary account holder can use this or administrator?
        $accountPersonId = $this->getUser()->getAccountPersonId();
        
        $accountPerson = $manager->loadAccountPerson($accountPersonId);
        if (!$accountPerson)
        {
           return $this->redirect($this->generateUrl('zayso_core_home'));            
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
                
                $userNameCurrent = $account->getUserName();
                $userPassCurrent = $account->getUserPass();
                
                $needFlush = false;
                
                if ($userName != $userNameCurrent)
                {
                    $account->setUserName($userName);
                    $needFlush = true;
                }
                if ($userPass && $userPass != $userPassCurrent)
                {
                    $account->setUserPass($userPass);
                    $needFlush = true;
                }
                if ($needFlush) 
                {
                   $manager->flush();  
                   
                    // Security check   
                    $subject = sprintf('[%s][Account] Edit %s TO %s FOR %s',
                        $this->getMyTitlePrefix(),
                        $userNameCurrent,
                        $userName,
                        $accountPerson->getPersonName());
                    
                    $this->sendEmail($subject,$subject);               
                }
                return $this->redirect($this->generateUrl('zayso_core_home'));
            }
        }
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['accountPerson'] = $accountPerson;
        
        return $this->renderx('Account:edit.html.twig',$tplData);
        
    }
}
?>

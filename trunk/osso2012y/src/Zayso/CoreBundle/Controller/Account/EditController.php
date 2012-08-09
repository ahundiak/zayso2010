<?php
namespace Zayso\CoreBundle\Controller\Account;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Controller\BaseController;

/* ========================================================
 * NatGames
 */
class EditController extends BaseController
{
    public function editAction(Request $request, $id = 0)
    {
        $manager = $this->get('zayso_core.account.manager');
        
        // Only the primary account holder can use this or administrator?
        $accountId = $this->getUser()->getAccountId();
        if ($accountId != $id)
        {
            return $this->redirect($this->generateUrl('zayso_core_home'));            
        }
        $account = $manager->loadAccountWithEverything($this->getMasterProjectId(),$accountId);
        if (!$account)
        {
           return $this->redirect($this->generateUrl('zayso_core_home'));            
        }
        $formType = $this->get('zayso_core.account.edit.formtype');
        
        $form = $this->createForm($formType, $account);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

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
                        $account->getPerson()->getPersonName());
                    
                    //$this->sendEmail($subject,$subject);               
                    
                    // Reload user
                    $this->setUser($account->getUserName());
                }
                return $this->redirect($this->generateUrl('zayso_core_account_edit',array('id' => $accountId)));
            }
        }
        $tplData = array();
        $tplData['form'] = $form->createView();
        $tplData['account'] = $account;
        
        return $this->renderx('ZaysoCoreBundle:Account:edit.html.twig',$tplData);
        
    }
}
?>

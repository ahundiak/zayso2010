<?php

namespace Zayso\AreaBundle\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

class PersonController extends BaseController
{
    public function editAction(Request $request, $id = 0)
    {
        return $this->redirect($this->generateUrl('zayso_area_home'));
    }
    public function addAction(Request $request)
    {
        $accountId = $this->getUser()->getAccountId();
        $manager = $this->getAccountManager(); // ('zayso_core.account.manager');
        
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
                
                $accountPerson = $manager->addAccountPersonAyso($accountPerson);
                
                if (is_object($accountPerson)) 
                {
                    // Security check   
                    $subject = sprintf('[Area] - Added %s',$accountPerson->getPersonName());
                    $this->sendEmail($subject,$subject);
                    
                    return $this->redirect($this->generateUrl('zayso_area_home'));
                }
            }
        }
        $tplData = array();
        $tplData['form'] = $form->createView();

        return $this->render('ZaysoAreaBundle:Account\Person:add.html.twig',$tplData);
    }
}
?>

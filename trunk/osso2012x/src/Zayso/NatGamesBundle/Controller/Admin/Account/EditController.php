<?php

namespace Zayso\NatGamesBundle\Controller\Admin\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class EditController extends BaseController
{
    public function editAction(Request $request, $id)
    {
        // Load in tha account person
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $id, 'projectId' => $this->getProjectId()));
        if (!$accountPerson)
        {
            die('Invalid account person id ' . $id);
        }
        
        // Form
        $formType = $this->get('admin.account.edit.formtype');
        $form = $this->createForm($formType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $accountManager->getEntityManager()->flush();
                
                return $this->redirect($this->generateUrl('natgames_admin_account_edit',array('id' => $id)));
            }
        }
        $tplData = $this->getTplData();
        $tplData['id']   = $id;
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Admin:Account/edit.html.twig',$tplData);
    }
}

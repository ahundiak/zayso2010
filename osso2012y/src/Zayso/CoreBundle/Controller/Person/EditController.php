<?php
namespace Zayso\CoreBundle\Controller\Person;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Controller\BaseController;

class EditController extends BaseController
{
    public function editAction(Request $request, $id = 0)
    {
        $manager = $this->get('zayso_core.person.manager');
        
        $person = $manager->loadPersonForEdit($this->getMasterProjectId(),$id);
        if (!$person)
        {
           return $this->redirect($this->generateUrl('zayso_core_home'));            
        }
      
        // Verify have permissions to edit this person
        /*
        $accountId = $this->getUser()->getAccountId();
        if ($accountId != $id)
        {
            return $this->redirect($this->generateUrl('zayso_core_home'));            
        }*/
        
        // Build the form
        //$formType = $this->get('zayso_core.account.edit.formtype');
        
        $form = $this->createForm('person_edit', $person);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if ($form->isValid())
            {    
                // die('Person ' . $person->getCellPhone());
                
                $manager->savePerson($person);

                // Update login stuff if needed
                $accountPersonId = $this->getUser()->getPersonId();
                if ($accountPersonId == $id)
                {
                    $this->setUser($this->getUser()->getUsername());
                }
                return $this->redirect($this->generateUrl('zayso_core_person_edit',array('id' => $person->getId())));
            }
        }
        $tplData = array();
        $tplData['formPerson'] = $form->createView();
        $tplData['person']     = $person;
        
        return $this->renderx('ZaysoCoreBundle:Person:edit.html.twig',$tplData);
        
    }
}
?>

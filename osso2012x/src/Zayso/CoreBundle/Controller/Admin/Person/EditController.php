<?php

namespace Zayso\CoreBundle\Controller\Admin\Person;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\PersonRegistered;

class EditController extends BaseController
{
    public function editAction(Request $request, $personId)
    {
        // Load the person
        $manager = $this->get('zayso_core.person.manager');
        $projectId = $this->getCurrentProjectId();
        $person = $manager->loadPersonForEdit($projectId,$personId);
        
        if (!$person)
        {
            $person = new Person();
            
            // Create one?
            // return $this->redirect($this->generateUrl('zayso_core_admin_person_search'));
        }
        
        // Just to confuse things, add current project if necessary
        // Note that it's also possible to remove current project
        $currentProjectPerson = $person->getCurrentProjectPerson();
        if (!$currentProjectPerson->getProject())
        {
            $project = $manager->getProjectReference($projectId);
            $currentProjectPerson->setProject($project);
        }
        // The form
        $formType = $this->get('zayso_core.admin.person.edit.formtype');
        $form = $this->createForm($formType,$person);

        // Process Post
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $manager->savePerson($person);
                
                return $this->redirect($this->generateUrl('zayso_core_admin_person_edit',array('personId' => $personId)));
            }
        }
        
        // And render
        $tplData = array();
        $tplData['person'] = $person;
        $tplData['form']   = $form->createView();
        return $this->renderx('ZaysoCoreBundle:Admin/Person:edit.html.twig',$tplData);
    }
}

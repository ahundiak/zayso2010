<?php

namespace Zayso\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Controller\BaseController as CoreBaseController;

class HomeController extends CoreBaseController
{
    /* ===================================================
     * Want to ensure the person is always in the current project (I think)
     * Also want to be abel to deal with setting project plans
     * Some of this might be better to do in the sign in controller?
     */
    protected function loadProjectPerson()
    {
        // Use the home manage
        $manager = $this->get('zayso_core.project.manager');
        
        // Ensure part of the project
        $user = $this->getUser();
        $personId  = $user->getPersonId();
        $projectId = $this->getMasterProjectId();
        
        return $manager->loadProjectPerson($projectId,$personId,true);
    }
    public function homeAction(Request $request)
    {        
        // Need to be signed in, security system does this
        if (!$this->isUser()) return $this->redirect($this->generateUrl('zayso_core_welcome'));
        
        // Deal with project stuff
        $this->loadProjectPerson();
        
        // Use the home manage
        $manager = $this->get('zayso_core.account.manager');
        
        // Ensure part of the project
        $user = $this->getUser();
        $accountId = $user->getAccountId();
        $projectId = $this->getMasterProjectId();
        
        // Get people for account
        $account = $manager->loadAccountWithEverything($projectId,$accountId);
        
        // And Render
        $tplData = array();
        $tplData['account'  ] = $account;
        $tplData['projectId'] = $projectId;
        
        return $this->renderx('Home:home.html.twig',$tplData);
    }
}

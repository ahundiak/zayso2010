<?php
namespace Zayso\NatGamesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

class HomeController extends BaseController
{
    public function indexAction()
    {
        // Need to be signed in, security system does this
        if (!$this->isUser()) return $this->redirect($this->generateUrl('zayso_core_welcome'));
        
        // Use the home manage
        $manager = $this->get('zayso_core.account.home.manager');
        
         // Ensure part of the project
        $user = $this->getUser();
        $accountId = $user->getAccountId();
        $projectId = $this->getProjectId();
        $personId  = $user->getPersonId();
        
        $projectPerson = $manager->addProjectPerson($projectId,$personId);
        
        // Verify plans were set
        $plans = $projectPerson->get('plans');
        if (!isset($plans['attend']) || !$plans['attend'])
        {
            return $this->redirect($this->generateUrl('zayso_core_project_plans'));
        }
        // Make sure have levels
        if (isset($plans['will_referee']) && $plans['will_referee'] == 'Yes')
        {
            $levels = $projectPerson->get('levels'); //print_r($levels)
            if (!is_array($levels))
            {
                return $this->redirect($this->generateUrl('zayso_core_project_levels'));
            }
        }
        
        // Get people for account
        $accountPersons = $manager->loadAccountPersons($accountId,$projectId);
        
        // And Render
        $tplData = array();
        $tplData['projectId']      = $projectId;
        $tplData['accountPersons'] = $accountPersons;
        
        return $this->renderx('Welcome:home.html.twig',$tplData);

    }
}
?>

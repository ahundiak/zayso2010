<?php

namespace Zayso\NatGamesBundle\Controller\Schedule;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Controller\BaseController;

/* ========================================================
 * S5Games
 * NatGames
 */
class RefAssignController extends BaseController
{
    protected $manager;
    
    public function assignAction(Request $request, $id = 0, $pos = null)
    {
        $this->manager = $manager = $this->get('zayso_core.game.manager');
        
        // Grab the game
        $gameId = $id;
        $game = $manager->loadEventForId($gameId);
        if (!$game)
        {
            return  $this->redirect($this->generateUrl('zayso_core_schedule_referee_list'));
        }
        // Need officials for the account
        $user = $this->getUser();
        if (!is_object($user)) return $this->redirect($this->generateUrl('zayso_core_schedule_referee_list'));
        $accountId = $user->getAccountId();
        $projectId = $this->getProjectId();
        $officials = $manager->loadOfficialsForAccount($projectId,$accountId);

        // The form stuff
        $formType = $this->get('zayso_natgames.schedule.refassign.formtype');
        $formType->setOfficials($officials);
        $form = $this->createForm($formType, $game);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {   
                $this->assignOfficials($game);
                
                // Should redirect
                return $this->redirect($this->generateUrl('zayso_core_schedule_referee_assign',array('id' => $gameId)));
            }
        }
        // And do it
        $tplData = array();
        $tplData['game'] = $game;
        $tplData['form'] = $form->createView();
        return $this->render('ZaysoNatGamesBundle:Schedule:assign.html.twig',$tplData);
    }
   
    protected function assignOfficial($game,$gamePersonRel)
    {
        $personIdx = $gamePersonRel->getPersonIdx(); // Posted value
        $personId  = $gamePersonRel->getPersonId();
        
        // Posted a person but no existing person
        if ($personIdx && !$personId)
        {
            $person = $this->manager->getPersonReference($personIdx);
            $gamePersonRel->setPerson($person);
            $gamePersonRel->setState('AssignmentRequested');
            $gamePersonRel->setUserModified();
            return true;
        }
        // Had one but don't anymore
        if (!$personIdx && $personId)
        {
            // The form should prevent this from happening
            // See if should remove or not
            
            return false;
        }
        // Nothing before or after
        if (!$personIdx && !$personId)
        {
           $gamePersonRel->setState(null);
           return true;
        }
        // Both have something
        if ($personIdx == $personId)
        {
            // Check state change request
            $statex = $gamePersonRel->getStatex();
            $state  = $gamePersonRel->getState ();
            
            if ($statex == 'RequestRemoval')
            {
                if ($state == 'AssignmentRequested')
                {
                    $gamePersonRel->setPerson(null);
                    $gamePersonRel->setState (null);
                    $gamePersonRel->setUserModified();
                    return true;
                }
                else
                {
                    $gamePersonRel->setState($statex);
                    $gamePersonRel->setUserModified();
                    return true;
                }
            }
            // Admin will use a different form
            if ($statex == 'AssignmentApproved')
            {
                if (!$this->isUserSuperAdmin()) return false;
                $gamePersonRel->setState($statex);
                $gamePersonRel->setAdminModified();
                return true;
            }
            return false;
        }
        // Both have something but are different
        // The form will prevent someone from trying to modify someone from outside their group
        $person = $this->manager->getPersonReference($personIdx);
        $gamePersonRel->setPerson($person);
        $gamePersonRel->setState('AssignmentRequested');
        $gamePersonRel->setUserModified();
        return true;
    }
    protected function assignOfficials($game)
    {
        $gamePersonRels = $game->getEventPersonsSorted();
        foreach($gamePersonRels as $gamePersonRel)
        {
            $this->assignOfficial($game,$gamePersonRel);
        }
        $this->manager->flush();
        
        return;
    }
}
?>

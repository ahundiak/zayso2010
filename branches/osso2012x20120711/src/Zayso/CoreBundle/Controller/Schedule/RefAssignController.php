<?php

namespace Zayso\CoreBundle\Controller\Schedule;

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
        
        // Custom pick list
        $officialsPickList = array(0 => 'Unassigned');
        foreach($officials as $official)
        {
            $officialsPickList[$official->getId()] = $official->getPersonName();
        }

        // Just pull out the individual assignments
        $formData = array('persons' => array());
        $gamePersons = $game->getEventPersonsSorted();
        foreach($gamePersons as $gamePerson)
        {
            $person = $gamePerson->getPerson();
            if ($person) $personId = $person->getId();
            else         $personId = 0;
            if ($person) $personName = $person->getPersonName();
            else         $personName = null;
            $personData = array(
                'type'       => $gamePerson->getType(),
                'typeDesc'   => $gamePerson->getTypeDesc(),
                'personId'   => $personId,
                'personName' => $personName,
            );
            $formData['persons'][] = $personData;
        }
        // The form stuff
        $formType = $this->get('zaysocore.schedule.refassign.formtype');
        $formType->setOfficials($officials);
        $form = $this->createForm($formType, $game);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {   
                $formData = $form->getData();
              //$manager->refresh($game);
                $this->assignOfficials($game,$formData);
                
                // Should redirect
                return $this->redirect($this->generateUrl('zayso_core_schedule_referee_assign',array('id' => $gameId)));

                // print_r($formData); die('Posted');
            }
        }
        // And do it
        $tplData = array();
        $tplData['game'] = $game;
        $tplData['form'] = $form->createView();
        return $this->render('ZaysoCoreBundle:Schedule:assign.html.twig',$tplData);
    }
    /* =====================================================
     * This is going to get ugly
     */
    protected function assignOfficialArray($game,$personData)
    {
        echo get_class($personData); die('assignOfficial');
        
        $type     = $personData['type'];
        $personId = $personData['personId'];
        
        $gamePersonRel = $game->getPersonForType($type);
        if (!$gamePersonRel)
        {
            // Adding new person not yet supported
            return;
        }
        $person = $gamePersonRel->getPerson();
        if ($person)
        {
            // No change
            if ($person->getId() == $personId) return;
            
            
        }
        else
        {
            // No current person assigned, was there an id
            $personId = (int)$personId;
            if (!$personId) return;
            
            // Assign them
            $manager = $this->get('zayso_core.game.manager');
            $person = $manager->getPersonReference($personId);
            $gamePersonRel->setPerson($person);
            $manager->flush();
            
            return;
        }
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
                    $gamePersonRel->setState(null);
                    return true;
                }
                else
                {
                    $gamePersonRel->setState($statex);
                    return true;
                }
            }
            if ($statex == 'AssignmentApproved')
            {
                if (!$this->isUserSuperAdmin()) return false;
                $gamePersonRel->setState($statex);
                return true;
            }
            return false;
        }
        // Both have something but are different
        // The form will prevent someone from trying to modify someone from outside their group
        $person = $this->manager->getPersonReference($personIdx);
        $gamePersonRel->setPerson($person);
        $gamePersonRel->setState('AssignmentRequested');
        return true;
    }
    protected function assignOfficials($game,$data)
    {
        $gamePersonRels = $game->getEventPersonsSorted();
        foreach($gamePersonRels as $gamePersonRel)
        {
            $this->assignOfficial($game,$gamePersonRel);
             
          //$personIdx = $gamePersonRel->getPersonIdx();
          //$personId  = $gamePersonRel->getPersonId();
          //echo sprintf('Person Rel #%d# #%d# %s<br />',$personId,$personIdx,$gamePersonRel->getState());
        }
        $this->manager->flush();
        
        return;
        die('xxx');
        $persons = $data['persons'];
        foreach($persons as $person)
        {
            $this->assignOfficial($game,$person);
        }
        
    }
}
?>

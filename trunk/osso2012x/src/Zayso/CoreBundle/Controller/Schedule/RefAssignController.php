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
    public function assignAction(Request $request, $id = 0, $pos = null)
    {
        $manager = $this->get('zayso_core.game.manager');
        
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
        $form = $this->createForm($formType, $formData);
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {   
                $formData = $form->getData();
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
    protected function assignOfficial($game,$personData)
    {
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
    protected function assignOfficials($game,$data)
    {
        $gamePersonRels = $game->getEventPersonsSorted();
        $persons = $data['persons'];
        foreach($persons as $person)
        {
            $this->assignOfficial($game,$person);
        }
        
    }
}
?>

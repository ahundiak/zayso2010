<?php

namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\AreaBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Format\HTML as FormatHTML;
use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;

use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\EventPerson;

class RefAssignController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_area.game.schedule.manager');
    }
    protected function getFormatHTML()
    {
        return $this->get('zayso_core.format.html');
    }
    protected function getGameViewHelper()
    {
        $format = $this->get('zayso_core.format.html');
        return new RefSchedGameViewHelper($format);
    }
    protected function getSearchViewHelper()
    {
        $format = $this->get('zayso_core.format.html');
        return new RefSchedSearchViewHelper($format);
    }
    protected function processEventPersonPosted($event,$eventPersonPosted)
    {
        $manager = $this->getScheduleManager();

        // If there is no new person then there can be no change
        if (!isset($eventPersonPosted['personIdNew'])) return;

        // If there was no change then ignore, move this downward to eliminate need for personIdOld
        // if ($eventPersonPosted['personId'] == $eventPersonPosted['personIdNew']) return;

        // Is it an existing record?
        foreach($event->getEventPersons() as $eventPerson)
        {
            if ($eventPerson->getId() == $eventPersonPosted['id'])
            {
                $personId = $eventPersonPosted['personIdNew'];

                $person = $eventPerson->getPerson();
                if ($person)
                {
                    // No need to update if they are the same
                    if ($person->getId() == $personId) return;
                }
                if ($personId) $person = $manager->getPersonReference($personId);
                else           $person = null;

                // Delete if no person and not protected
                if (!$eventPerson->isProtected() && !$person)
                {
                    $manager->remove($eventPerson);
                    $manager->flush();
                    return;
                }
                $eventPerson->setPerson($person);
                $manager->flush();
                return;
            }
        }
        // Not existing but did change, should mean to insert new record
        $personId = $eventPersonPosted['personIdNew'];

        // Don't think this should really happen
        if (!$personId) return;

        // Neither should this?
        $eventPersonId = $eventPersonPosted['id'];
        if ($eventPersonId > 0) return;

        // Or this
        $type = $eventPersonPosted['type'];
        if (!$type) return;

        // Go for it
        $eventPerson = new EventPerson();
        $eventPerson->setType($type);
        $eventPerson->setEvent($event);
        $eventPerson->setPerson($manager->getPersonReference($personId));
        $event->addPerson($eventPerson);
        $manager->persist($eventPerson);
        $manager->flush();
        
    }
    public function assignPostAction(Request $request)
    {
        // Make sure have a valid game id
        $manager = $this->getScheduleManager();
        $gameId = $request->request->get('gameId');
        $game = $manager->loadEventForId($gameId);
        if (!$game)
        {
            return $this->redirect($this->generateUrl('zayso_area_schedule_referee_list'));
        }
        // Grab and process the event persons
        $eventPersonsPosted = $request->request->get('eventPerson');
        foreach($eventPersonsPosted as $eventPersonPosted)
        {
            $this->processEventPersonPosted($game,$eventPersonPosted);
        }
        // And redirect
        return $this->redirect($this->generateUrl('zayso_area_schedule_referee_assign',array('id' => $gameId)));
    }
    public function assignAction(Request $request, $id = 0, $pos = null)
    {
        // Need to get the firewall in place
        
        if ($request->getMethod() == 'POST') return $this->assignPostAction($request);

        $manager = $this->getScheduleManager();
        $game = $manager->loadEventForId($id);
        if (!$game)
        {
            return $this->redirect($this->generateUrl('zayso_area_schedule_referee_list'));
        }
        // Just for grins
        $eventPersonId = 0;
        $types = array(EventPerson::Type4th,EventPerson::TypeObs);
        foreach($types as $type)
        {
            $eventPerson = $game->getPersonForType($type);
            if (!$eventPerson)
            {
                $eventPerson = new EventPerson();
                $eventPerson->setId(--$eventPersonId);
                $eventPerson->setType($type);
                $eventPerson->setEvent($game);
                $game->addPerson($eventPerson);
            }
        }
        // Make life a bit easier by always having a person?
        foreach($game->getPersons() as $eventPerson)
        {
            if (!$eventPerson->getPerson())
            {
                $person = new Person();
                //$eventPerson->setPerson($person);
            }
        }
        // Need a pick list for the account referees
        $user = $this->getUser();
        if (is_object($user))
        {
            $accountId = $user->getAccountId();
            $officials = $manager->getOfficialsForAccount(0,$accountId);
        }
        else $officials = array();
        
        $officialsPickList = array(0 => 'Unassigned');
        foreach($officials as $official)
        {
            $officialsPickList[$official->getId()] = $official->getPersonName();
        }

        // Try to set user to default position
        if ($pos && count($officials))
        {
            $eventPerson = $game->getPersonForType($pos);
            if ($eventPerson && !$eventPerson->getPerson())
            {
                // Getting way to clever here
                $officialId = $officials[0]->getId();
                $officialsPickList[$officialId] = $officialsPickList[$officialId] . '*';
                $eventPerson->setPerson($officials[0]);
            }
        }
        // Not completely sure about this
        $gameView = $this->getGameViewHelper();
        $gameView->setGame($game);
        $gameView->officialsPickList = $officialsPickList;
        
        // Gather up data
        $tplData = array();

        $tplData['gameView'] = $gameView;
        $tplData['game']     = $game; // Keep for now for event persons
        $tplData['pos']      = $pos;

        $tplData['gen']    = $this;
        $tplData['format'] = $this->format = $this->getFormatHTML();

        return $this->render('ZaysoAreaBundle:Schedule:assign.html.twig',$tplData);
    }
}
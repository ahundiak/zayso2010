<?php

namespace Zayso\Area5CFBundle\Controller;

use Zayso\ZaysoBundle\Entity\Game;
use Zayso\ZaysoBundle\Entity\GamePerson;

class ScheduleController extends BaseController
{
    public function getGames($refSchedData)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('game');
        $qb->addSelect('gameTeam');
        $qb->addSelect('person');

        $qb->from('ZaysoBundle:Game','game');

        $qb->leftJoin('game.gameTeams','gameTeam');
        $qb->leftJoin('game.persons',  'person');

        $qb->addOrderBy('game.date');
        $qb->addOrderBy('game.time');
        $qb->addOrderBy('game.fieldKey');
        $qb->addOrderBy('game.age');

        $query = $qb->getQuery();

        $items = $query->getResult();
        return $items;
    }
    public function indexAction()
    {
        $session = $this->getSession();
        $refSchedData = $session->get('refSchedData');

        if ($refSchedData)
        {
            $errors = $session->getFlash('errors');
            $refSchedData['errors'] = $errors;
        }
        else
        {
            $refSchedData = array
            (
                'errors'   => array(),
            );
        }
        $games = $this->getGames($refSchedData);
        $this->refSchedData = $refSchedData;

        $tplData = $this->getTplData();

        $tplData['games']   = $games;
        $tplData['ages']    = array('All','U05','U06','U07','U08','U10','U12','U14','U16','U19');
        $tplData['genders'] = array('All','Boys','Coed','Girls');
        $tplData['regions'] = array('All','R0160','R0498','R0894','R0914','R1174');
        
        $tplData['gen']  = $this;
        return $this->render('Area5CFBundle:Schedule:schedule.html.twig',$tplData);
    }
    public function indexPostAction()
    {
        $request = $this->getRequest();
        $session = $this->getSession();

        $refSchedData = $request->request->get('refSchedData');

        $session->set('refSchedData',$refSchedData);

        return $this->redirect($this->generateUrl('_area5cf_schedule'));
    }
    public function genAgeCheckBox($age)
    {
        if (isset($this->refSchedData['ages'][$age])) $checked = 'checked="checked"';
        else                                          $checked = null;
        
        $html = sprintf('%s<br /><input type="checkbox" name="refSchedData[ages][%s]" value="%s" %s />',$age,$age,$age,$checked);
        return $html;
    }
    public function genGenderCheckBox($gender)
    {
        if (isset($this->refSchedData['genders'][$gender])) $checked = 'checked="checked"';
        else                                                $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="refSchedData[genders][%s]" value="%s" %s />',$gender,$gender,$gender,$checked);
        return $html;
    }
    public function genRegionCheckBox($region)
    {
        if (isset($this->refSchedData['regions'][$region])) $checked = 'checked="checked"';
        else                                                $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="refSchedData[regions][%s]" value="%s" %s />',$region,$region,$region,$checked);
        return $html;
    }
    public function genTeam($team)
    {
        if (!$team) return '';

        $key = $team->getTeamKey();
        if (!$key) return $key;

        $key = sprintf('%s-%s-%s %s',substr($key,0,5),substr($key,5,4),substr($key,9,2),substr($key,12));
        return $key;
    }
    public function genReferees($game)
    {
        $refs = array();
        $types = array('CR','AR1','AR2');
        foreach($types as $type)
        {
            $ref = $game->getGamePersonForType($type);
            if (!$ref)
            {
                $ref = new GamePerson();
                $ref->setType($type);
            }
            $refs[$type] = $ref;
        }
        $html = '<table>';
        foreach($refs as $ref)
        {
            $type = $ref->getType();
            $name = $ref->getLastName();
            $row = sprintf('<tr><td>%s</td><td>%s</td></tr>',$type,$name);
            $html .= $row;
        }
        $html .= '</table>';
        return $html;
    }
}

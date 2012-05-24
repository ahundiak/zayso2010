<?php
namespace Zayso\CoreBundle\Controller\Person;

use Zayso\CoreBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\PersonTeamRel;

use Symfony\Component\HttpFoundation\Request;

/* ===========================================================
 * Area
 * S5Games
 * NatGames
 */
class TeamController extends BaseController
{
    public function listAction(Request $request, $personId = 0)
    {
        if (!$personId) $personId = $this->getUser()->getPersonId();
        
        $projectId = $this->getCurrentProjectId();
        
        $manager = $this->get('zayso_core.person.manager');
        
        $person = $manager->loadPersonForProject($projectId,$personId);
        if (!$person)
        {
            return $this->redirect($this->generateUrl('zayso_core_home'));            

        }
        $personTeamRel = new PersonTeamRel();
        $personTeamRel->setId(-1);
        $personTeamRel->setProject($manager->getProjectReference($projectId));
        $personTeamRel->setPerson ($person);
        $person->addTeamRel($personTeamRel);
        
        $listFormType = $this->get('zaysocore.person.team.list.formtype');
        
        $listForm = $this->createForm($listFormType, $person);

        if ($request->getMethod() == 'POST')
        {
            $listForm->bindRequest($request);
            
            if ($listForm->isValid())
            {
                $teamRels = $person->getTeamRels();
                foreach($teamRels as $teamRel)
                {
                    // Make sure it can be persisted
                    if (!$teamRel-> getType() || !$teamRel->getTeam() || $teamRel->getDelete())
                    {
                        $person->removeTeamRel($teamRel);
                        if ($teamRel->getId() > 0) $manager->remove($teamRel);
                    }
                    else
                    {
                        if ($teamRel->getId() < 0) $manager->persist($teamRel);
                    }
                }
                $manager->flush();
                return $this->redirect($this->generateUrl('zayso_core_person_team_list',array('personId' => $personId)));
            }
        }

        $tplData = array();
        $tplData['person']   = $person;
        $tplData['listForm'] = $listForm->createView();
        
        return $this->renderx('Person\Team:list.html.twig',$tplData);
    }
}

?>

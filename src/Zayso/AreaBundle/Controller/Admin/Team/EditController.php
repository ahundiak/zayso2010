<?php

namespace Zayso\AreaBundle\Controller\Admin\Team;

use Zayso\AreaBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class EditController extends BaseController
{
    public function editAction(Request $request, $id)
    {
        // Load in the team
        $manager = $this->getTeamManager();
        $team = $manager->queryTeam($id);
        
        if (!$team)
        {
            die('Invalid team id ' . $id);
        }
        // Form
        $formType = $this->get('zayso_area.admin.team.edit.formtype');
        $form = $this->createForm($formType, $team);

        if ($this->isAdmin() && $request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                if ($request->request->get('update_submit'))
                {
                    $manager->flush();
                }
                if ($request->request->get('delete_submit'))
                {
                    $manager->remove($team);
                    $manager->flush();
                    return $this->redirect($this->generateUrl('zayso_area_admin_team_list'));
                }
                if ($request->request->get('clone_submit'))
                {
                    $teamx = $manager->cloneTeam($team);
                    $manager->detach ($team);
                    $manager->persist($teamx);
                    $manager->flush();
                   
                    $id = $teamx->getId();
                }
                return $this->redirect($this->generateUrl('zayso_area_admin_team_edit',array('id' => $id)));
            }
        }
        $tplData = array();
        $tplData['team'] = $team;
        $tplData['form'] = $form->createView();

        return $this->render('ZaysoAreaBundle:Admin:Team/edit.html.twig',$tplData);
    }
}

<?php

namespace Zayso\Osso2007Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Zayso\Osso2007Bundle\Component\Debug;

class EventController extends BaseController
{
    public function editAction($id = 0)
    {
        // Permissions
        $user = $this->getUser();
        if (!$user->isAdmin()) return $this->redirect($this->generateUrl('_osso2007_welcome'));

        $gameManager = $this->getGameManager();
        $game = $gameManager->loadGameForId($id);
        $projectId = $game->getProjectId();

        $search = array();
        $search['projectId'] = $projectId;
        $search['regions'] = array($game->getRegionKey());

        $tplData = $this->getTplData();
        $tplData['game'] = $game;
        $tplData['datePickList']  = $gameManager->getDatePickList($projectId);
        $tplData['timePickList']  = $gameManager->getTimePickList($projectId);
        $tplData['fieldPickList'] = $gameManager->getFieldPickList($search);
        
        return $this->render('Osso2007Bundle:Game:edit.html.twig',$tplData);
    }
}

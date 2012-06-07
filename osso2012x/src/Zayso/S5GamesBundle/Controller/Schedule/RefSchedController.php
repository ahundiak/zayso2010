<?php

namespace Zayso\S5GamesBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefSchedController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_core.game.schedule.manager');
    }
    protected function initSearchData()
    {
        return array(
            'dows'    => array('FRI','SUN'),
            'ages'    => array(),
            'genders' => array(),
            'time1'   => '0600',
            'time2'   => '2100',
       );
    }
    public function list2011Action(Request $request, $search = null)
    {
        $searchData = $this->initSearchData();
        
        // Pull from session if nothing was passed
        if (!$search) $search = $request->getSession()->get('refSchSearchData');
        
        if ($search) $searchData = array_merge($searchData,json_decode($search,true));

        $searchFormType = $this->get('zayso_s5games.schedule.search.formtype');
        
        $searchForm = $this->createForm($searchFormType,$searchData);
        
        if ($request->getMethod() == 'POST')
        {
            $searchForm->bindRequest($request);

            if ($searchForm->isValid())
            {
                // JSON_HEX_TAG ???
                // JSON_UNESCAPED_UNICODE 5.4
                // JSON_NUMERIC_CHECK Don't use, 0900 > 900
                $search = $searchForm->getData();
                $search = json_encode($search);
                $request->getSession()->set('refSchSearchData',$search);
                return $this->redirect($this->generateUrl('zayso_core_schedule_referee_list2011',array('search' => $search)));
            }
        }
        $manager = $this->getScheduleManager();
        
        // Should projectId be in regular search data?  Probably
        $searchData['projectId'] = 61;
        
        $games = $manager->loadGames($searchData);
      //$games = array();
        
        $tplData = array();
        $tplData['games'] = $games;
        $tplData['gameCount'] = count($games);
        $tplData['searchForm'] = $searchForm->createView();
        return $this->renderx('Schedule:referee.html.twig',$tplData);
        
    }
    public function listAction(Request $request, $search = null)
    {
        $searchData = $this->initSearchData();
        
        // Pull from session if nothing was passed
        if (!$search) $search = $request->getSession()->get('refSchSearchData');
        
        if ($search) $searchData = array_merge($searchData,json_decode($search,true));

        $searchFormType = $this->get('zayso_s5games.schedule.search.formtype');
        
        $searchForm = $this->createForm($searchFormType,$searchData);
        
        if ($request->getMethod() == 'POST')
        {
            $searchForm->bindRequest($request);

            if ($searchForm->isValid())
            {
                // JSON_HEX_TAG ???
                // JSON_UNESCAPED_UNICODE 5.4
                // JSON_NUMERIC_CHECK Don't use, 0900 > 900
                $search = $searchForm->getData();
                $search = json_encode($search);
                $request->getSession()->set('refSchSearchData',$search);
                return $this->redirect($this->generateUrl('zayso_core_schedule_referee_list',array('search' => $search)));
            }
        }
        $manager = $this->getScheduleManager();
        
        // Should projectId be in regular search data?  Probably
        $searchData['projectId'] = 62;
        
        $games = $manager->loadGames($searchData);
      //$games = array();
        
        $tplData = array();
        $tplData['games'] = $games;
        $tplData['gameCount'] = count($games);
        $tplData['searchForm'] = $searchForm->createView();
        return $this->renderx('Schedule:referee.html.twig',$tplData);
        
    }
}

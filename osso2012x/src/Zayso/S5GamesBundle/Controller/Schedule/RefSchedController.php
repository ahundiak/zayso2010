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
    public function list2011Actionx(Request $request)
    {
        die('list2011');
        
        $session = $request->getSession();
        
        // Make data sticky on post
        if ($request->getMethod() == 'POST') 
        {
            $refSchedSearchData = $request->request->get('refSchedSearchData');
            
            $refSchedSearchData['date1'] = 
                $refSchedSearchData['date1x']['year' ] . 
                $refSchedSearchData['date1x']['month'] . 
                $refSchedSearchData['date1x']['day'];
            
            $refSchedSearchData['date2'] = 
                $refSchedSearchData['date2x']['year' ] . 
                $refSchedSearchData['date2x']['month'] . 
                $refSchedSearchData['date2x']['day'];
            
            $refSchedSearchData['time1'] = 
                $refSchedSearchData['time1x']['hour' ] . '00';
            
            $refSchedSearchData['time2'] = 
                $refSchedSearchData['time2x']['hour' ] . '00'; 

            // Just to avoid down stream confusion
            unset($refSchedSearchData['date1x']);
            unset($refSchedSearchData['date2x']);
            unset($refSchedSearchData['time1x']);
            unset($refSchedSearchData['time2x']);
            
            // Allow for single date change
            if ($refSchedSearchData['date1'] > $refSchedSearchData['date2'])  
            {
                $refSchedSearchData['date2'] = $refSchedSearchData['date1'];
            }
            // Store everything
            $session->set('refSchedSearchData',$refSchedSearchData);
            
            // Redirect so refresh behaves
            return $this->redirect($this->generateUrl('zayso_area_schedule_referee_list'));
        }
        else
        {
            $refSchedSearchData = $session->get('refSchedSearchData');

            if (!$refSchedSearchData || 0)
            {
                $time1 = time();
                $time2 = $time1 + (60 * 60 * 24 * 14);
                
                $date1 = date('Ymd',$time1);
                $date2 = date('Ymd',$time2);
                 
              //die('new Data: ' . $date1 . ' ' . $date2);
                
                $refSchedSearchData = array
                (
                    'sortBy'  => 1,
                    'date1'   => $date1,
                    'date2'   => $date2,
                    'time1'   => '0600',
                    'time2'   => '2100',
                    'ages'    => array(),
                    'regions' => array(),
                    'genders' => array(),
                );
            }
        }
        // Grab the games
        $games = $this->getScheduleManager()->queryGames($refSchedSearchData);

        // Pass it all on
        $tplData = array();

        $tplData['games']     = $games;
        $tplData['gameCount'] = count($games);
        
//      $tplData['gameView']   = $this->getGameViewHelper();
        $tplData['searchView'] = $this->getSearchViewHelper();
       
        $tplData['refSchedSearchData']  = $refSchedSearchData;
        
        return $this->render('ZaysoAreaBundle:Schedule:referee.html.twig',$tplData);
        return $this->render('ZaysoAreaBundle:Schedule:schedule.html.twig',$tplData);
    }
    public function viewRefSchedPostAction()
    {
        $request = $this->getRequest();
        $session = $this->getSession();


        $refSchedData = $request->request->get('refSchedData');

        $session->set('refSchedData',$refSchedData);

        return $this->redirect($this->generateUrl('_area5cf_schedule'));
    }
    /* ===================================================================
     * Assorted call backs for displaying information
     */
    public function genAgeCheckBox($age)
    {
        if (isset($this->schedSearchData['ages'][$age])) $checked = 'checked="checked"';
        else                                             $checked = null;
        
        $html = sprintf('%s<br /><input type="checkbox" name="schedSearchData[ages][%s]" value="%s" %s />',$age,$age,$age,$checked);
        return $html;
    }
    public function genGenderCheckBox($gender)
    {
        if (isset($this->schedSearchData['genders'][$gender])) $checked = 'checked="checked"';
        else                                                   $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="schedSearchData[genders][%s]" value="%s" %s />',
                $gender,$gender,substr($gender,0,1),$checked);
        return $html;
    }
    public function genRegionCheckBox($region)
    {
        if (isset($this->schedSearchData['regions'][$region])) $checked = 'checked="checked"';
        else                                                   $checked = null;

        $html = sprintf('%s<br /><input type="checkbox" name="schedSearchData[regions][%s]" value="AYSO%s" %s />',$region,$region,$region,$checked);
        return $html;
    }
}

<?php

namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\AreaBundle\Controller\BaseController;
use Zayso\CoreBundle\Component\Format\HTML as FormatHTML;
use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\EventPerson;

class RefSchedController extends BaseController
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
    public function listAction(Request $request, $_format)
    {
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

        if ($_format == 'xls') return $this->generateExcel($games);
        
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
    /* ========================================================
     * Hack upon hack
     */
    protected function generateExcel($games)
    {
        $excel = $this->get('zayso_core.format.excel');
        
        // Build the spreadsheet
        $ss = $excel->newSpreadSheet();
        $ws = $ss->setActiveSheetIndex(0);
        $ws->setTitle('Schedule');
        
        // The headers
        $headers = array
        (
            'Game#','Date','Time','Field','Pool','Home Team','Away Team'
        );
        $row = 1;
        $col = 0;
        foreach($headers as $header)
        {
            $ws->setCellValueByColumnAndRow($col,$row,$header);
            $col++;
        }
        // The games
        foreach($games as $game)
        {
            $row++;
            $col = 0;
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getNum());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getDate());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getTime());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getFieldDesc());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getPool());
            
            $homeTeam = $game->getHomeTeam()->getTeam();
            $awayTeam = $game->getAwayTeam()->getTeam();
            
            $homeTeamDesc = $homeTeam->getDesc();
            $awayTeamDesc = $awayTeam->getDesc();
            
            if (!$homeTeamDesc) $homeTeamDesc = $homeTeam->getKey();
            if (!$awayTeamDesc) $awayTeamDesc = $awayTeam->getKey();
            
            $ws->setCellValueByColumnAndRow($col++,$row,$homeTeamDesc);
            $ws->setCellValueByColumnAndRow($col++,$row,$awayTeamDesc);
        }
        // Generate Content
        $ss->setActiveSheetIndex(0);
        $objWriter = $excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        $content = ob_get_clean();

        // Produce response
        $outFileName = 'AreaSchedule' . date('Ymd') . '.xls';
        
        $response = new Response();
        $response->setContent($content);
        
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
        
        return $response;
    }
}

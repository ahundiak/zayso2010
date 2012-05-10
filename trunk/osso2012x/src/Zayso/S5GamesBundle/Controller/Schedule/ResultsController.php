<?php
namespace Zayso\S5GamesBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultsController extends BaseController
{
    protected function getScheduleManager()
    {
        return $this->get('zayso_core.game.schedule.manager');
    }
    public function results2011Action(Request $request, $div, $pool)
    {
        $data = array('div' => $div, 'pool' => $pool);
        $formType = $this->get('zayso_core.results.search.formtype');
        $form = $this->createForm($formType, $data);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {   
                $data = $form->getData();
                
                $request->getSession()->set('resultsSearchData',$data);
                
                return $this->redirect($this->generateUrl('zayso_s5games_schedule_results2011',$form->getData()));
            }
        }
        if (!$div)
        {
            $data = $request->getSession()->get('resultsSearchData');
            if (is_array($data) && isset($data['div']) && $data['div'])
            {
                return $this->redirect($this->generateUrl('zayso_s5games_schedule_results2011',$data));
            }
        }
        $manager = $this->getScheduleManager();
        if (strlen($div) == 4)
        {
            $params = array
            (
                'projectId' => 61,
                'ages'      => array(substr($div,0,3)),
                'genders'   => array(substr($div,3,1)),
            );
            $games = $manager->loadGames($params);
        }
        else $games = array();
        
        $pools = array();
        $poolFilter = $pool;
        foreach($games as $game)
        {
            $pool = $game->getPool();
            if (substr($pool,5,2) == 'PP') 
            {
                if (!$poolFilter || $poolFilter == substr($pool,8,1))
                {
                $pools[$pool]['games'][] = $game;
                
                $homeGameTeam = $game->getHomeTeam();
                $awayGameTeam = $game->getAwayTeam();

                $homeSchTeam = $homeGameTeam->getTeam();
                $awaySchTeam = $awayGameTeam->getTeam();
                
                if ($homeSchTeam && $awaySchTeam)
                {
                    if ($game->isPointsApplied())
                    {
                        $this->calcSchTeamPoints($pool,$homeGameTeam,$homeSchTeam);
                        $this->calcSchTeamPoints($pool,$awayGameTeam,$awaySchTeam);
                    }
                    $pools[$pool]['teams'][$homeSchTeam->getId()] = $homeSchTeam;
                    $pools[$pool]['teams'][$awaySchTeam->getId()] = $awaySchTeam;
                }
            }}
        }
        ksort($pools);
        
        // Sort the teams within each pool
        foreach($pools as $poolKey => $pool)
        {
            $teams = $pool['teams'];
            
            //sort
            $this->poolKey = $poolKey;
            usort($teams,array($this,'compareTeamStandings'));
            
            $pools[$poolKey]['teams'] = $teams;
        }
        // Maybe apply some tie breaking?
        
        $tplData = array();
        $tplData['searchForm'] = $form->createView();
        $tplData['pools'] = $pools;
        $tplData['games'] = $games;
        $tplData['gameCount'] = count($games);
        
        return $this->render('ZaysoS5GamesBundle:Schedule:results.html.twig',$tplData);
        
    }
    protected function calcSchTeamPoints($pool,$gameTeam,$schTeam)
    {
        $schTeam->addPointsEarned($pool,$gameTeam->getPointsEarned());   
        $schTeam->addPointsMinus ($pool,$gameTeam->getPointsMinus());
        
        $schTeam->addGoalsScored ($pool,$gameTeam->getGoalsScored());
        $schTeam->addGoalsAllowed($pool,$gameTeam->getGoalsAllowed());
        
        $schTeam->addCautions($pool,$gameTeam->getCautions());
        $schTeam->addSendoffs($pool,$gameTeam->getSendoffs());
        
        $schTeam->addSportsmanship($pool,$gameTeam->getSportsmanship());
        
        if ($gameTeam->getGoalsScored() !== null)
        {
            $schTeam->addGamesPlayed($pool,1);
        }
     }

    protected $poolKey = null;
    protected function compareTeamStandings($team1,$team2)
    {
        $pe1 = $team1->getPointsEarned($this->poolKey);
        $pe2 = $team2->getPointsEarned($this->poolKey);
        if ($pe1 < $pe2) return  1;
        if ($pe1 > $pe2) return -1;
        
        $key1 = $team1->getTeamKey();
        $key2 = $team2->getTeamKey();
        
        if ($key1 < $key2) return -1;
        if ($key1 > $key2) return  1;
         
        return 0;
    }
}
?>

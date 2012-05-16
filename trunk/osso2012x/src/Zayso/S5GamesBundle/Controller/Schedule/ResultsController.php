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
            if ($game->isPoolPlay())
            {
                if (!$poolFilter || $poolFilter == substr($pool,8,1))
                {
                $pools[$pool]['games'][] = $game;
                
                $homeGameTeam = $game->getHomeTeam()->getTeam();
                $awayGameTeam = $game->getAwayTeam()->getTeam();

                $homePoolTeam = $homeGameTeam->getParent();
                $awayPoolTeam = $awayGameTeam->getParent();
                
                if ($homePoolTeam && $awayPoolTeam)
                {
                    if ($game->isPointsApplied())
                    {
                        $this->calcPoolTeamPoints($homeGameTeam,$homePoolTeam);
                        $this->calcPoolTeamPoints($awayGameTeam,$awayPoolTeam);
                    }
                    $pools[$pool]['teams'][$homePoolTeam->getId()] = $homePoolTeam;
                    $pools[$pool]['teams'][$awayPoolTeam->getId()] = $awayPoolTeam;
                }
            }}
        }
        ksort($pools);
        
        // Sort the teams within each pool
        foreach($pools as $poolKey => $pool)
        {
            $teams = $pool['teams'];
            
            //sort
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
    protected function calcPoolTeamPoints($gameTeam,$poolTeam)
    {
        $poolTeam->addPointsEarned($gameTeam->getPointsEarned());   
        $poolTeam->addPointsMinus ($gameTeam->getPointsMinus());
        
        $poolTeam->addGoalsScored ($gameTeam->getGoalsScored());
        
        $goalsAllowed = $gameTeam->getGoalsAllowed();
        if ($goalsAllowed > 5) $goalsAllowed = 5;
        $poolTeam->addGoalsAllowed($goalsAllowed);
        
        $poolTeam->addCautions($gameTeam->getCautions());
        $poolTeam->addSendoffs($gameTeam->getSendoffs());
        
        $poolTeam->addCoachTossed($gameTeam->getCoachTossed());
        $poolTeam->addSpecTossed ($gameTeam->getSpecTossed());
        
        $poolTeam->addSportsmanship($gameTeam->getSportsmanship());
        
        if ($gameTeam->getGoalsScored() !== null)
        {
            $poolTeam->addGamesPlayed(1);
            if ($gameTeam->getGoalsScored() > $gameTeam->getGoalsAllowed()) $poolTeam->addGamesWon(1);
        }
    }
    protected function compareTeamStandings($team1,$team2)
    {
        // Points earned
        $pe1 = $team1->getPointsEarned();
        $pe2 = $team2->getPointsEarned();
        if ($pe1 < $pe2) return  1;
        if ($pe1 > $pe2) return -1;
        
        // Head to head
        
        // Games won
        $gw1 = $team1->getGamesWon();
        $gw2 = $team2->getGamesWon();
        if ($gw1 < $gw2) return  1;
        if ($gw1 > $gw2) return -1;
        
        // Sportsmanship deductions
        $pm1 = $team1->getPointsMinus();
        $pm2 = $team2->getPointsMinus();
        if ($pm1 < $pm2) return  1;
        if ($pm1 > $pm2) return -1;
         
        // Goals Allowed
        $ga1 = $team1->getGoalsAllowed();
        $ga2 = $team2->getGoalsAllowed();
        if ($ga1 < $ga2) return -1;
        if ($ga1 > $ga2) return  1;
        
        // Just the key
        $key1 = $team1->getKey();
        $key2 = $team2->getKey();
        
        if ($key1 < $key2) return -1;
        if ($key1 > $key2) return  1;
         
        return 0;
    }
}
?>

<?php
namespace Zayso\NatGamesBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultsController extends BaseController
{
    public function poolplayAction(Request $request, $div, $pool)
    {
        if (!$div)
        {
            $data = $request->getSession()->get('resultsSearchData');
            if (is_array($data) && isset($data['div']) && $data['div'])
            {
                // A redirect seems to be the cleanest
                return $this->redirect($this->generateUrl('zayso_core_schedule_poolplay',$data));
            }
        }
        else $request->getSession()->set('resultsSearchData',array('div' => $div, 'pool' => $pool));
        
        $manager = $this->get('zayso_natgames.game.schedule.results.manager');
        if (strlen($div) == 4)
        {
            $age    = substr($div,0,3);
            $gender = substr($div,3,1);
            $params = array
            (
                'projectId' => 52,
                'ages'      => array($age),
                'genders'   => array($gender),
            );
            
            $games = $manager->loadGames($params);
        }
        else $games = array();
        
        $pools = $manager->getPools($games,$pool);
        
        $tplData = array();
        $tplData['pools']  = $pools;
        
        $response = $this->renderx('Schedule:results.html.twig',$tplData);
      //$response->setPublic();
      //$response->setSharedMaxAge(30);
        return $response;
         
    }
    public function playoffsAction(Request $request, $div, $pool)
    {
        
        $manager = $this->get('zayso_natgames.game.schedule.results.manager');
        $params = array
        (
            'projectId' => 52,
            'ages'      => array('U12','U14','U16','U19'),
            'dates'     => array('20120707','20120708'),
            'orderBy'   => 'playoffs',
        );
            
        $games = $manager->loadGames($params);
        $gamesx = array();
        foreach($games as $game)
        {
            if (!$game->isPoolPlay())
            {
                $gamesx[] = $game;
            }
        }
        $games = $gamesx;
        
        $tplData = array();
        $tplData['games']  = $games;
        
        $response = $this->renderx('Schedule:playoffs.html.twig',$tplData);
      //$response->setPublic();
      //$response->setSharedMaxAge(30);
        return $response;
         
    }
}
?>

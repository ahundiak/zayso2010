<?php
namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultsController extends BaseController
{
    public function resultsAction(Request $request, $div, $pool, $cache)
    {   
        if (!$div)
        {
            $data = $request->getSession()->get('resultsSearchData');
            if (is_array($data) && isset($data['div']) && $data['div'])
            {
                // A redirect seems to be the cleanest
                if (!$cache) return $this->redirect($this->generateUrl('zayso_core_schedule_results',$data));
                else         return $this->redirect($this->generateUrl('zayso_core_schedule_results_cached',$data));
                
                // Works but url doesn't update
                $div = $data['div'];
                if (isset($data['pool'])) $pool = $data['pool'];
            }
        }
        else $request->getSession()->set('resultsSearchData',array('div' => $div, 'pool' => $pool));
        
        $manager = $this->get('zayso_core.game.schedule.results.manager');
        if (strlen($div) == 4)
        {
            $age    = substr($div,0,3);
            $gender = substr($div,3,1);
            $params = array
            (
                'projectId' => 79,
                'ages'      => array($age),
                'genders'   => array($gender),
            );
            if ($gender == 'B') $params['genders'][] = 'C';
            
            $games = $manager->loadGames($params);
        }
        else $games = array();
        
        $pools = $manager->getPools($games,$pool);
        
        $tplData = array();
        $tplData['dtg']   = date('Y-m-d H:i:s',time());
        $tplData['pools'] = $pools;
        $tplData['cache'] = $cache;
        
      //$tplData['searchForm'] = $form->createView();
        
        $response = $this->renderx('Schedule:results.html.twig',$tplData);
        if (!$cache) return $response;
        
        $response->setPublic();
        $response->setSharedMaxAge(120);
        return $response;

    }
}
?>

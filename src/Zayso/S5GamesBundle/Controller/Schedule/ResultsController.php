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
    public function resultsAction(Request $request, $div, $pool)
    {
        if (!$div)
        {
            $data = $request->getSession()->get('resultsSearchData');
            if (is_array($data) && isset($data['div']) && $data['div'])
            {
                // A redirect seems to be the cleanest
                return $this->redirect($this->generateUrl('zayso_core_schedule_results',$data));
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
                'projectId' => 62,
                'ages'      => array($age),
                'genders'   => array($gender),
            );
            if ($gender == 'B') $params['genders'][] = 'C';
            
            $games = $manager->loadGames($params);
        }
        else $games = array();
        
        $pools = $manager->getPools($games,$pool);
        
        $tplData = array();
      //$tplData['dtg']    = date('Y-m-d H:i:s',time());
        $tplData['pools']  = $pools;
      //$tplData['cached'] = true;
        
        $response = $this->renderx('Schedule:results.html.twig',$tplData);
        $response->setPublic();
        $response->setSharedMaxAge(15);
        return $response;
         
    }
}
?>

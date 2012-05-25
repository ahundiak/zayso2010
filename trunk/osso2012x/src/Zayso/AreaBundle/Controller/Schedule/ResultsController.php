<?php
namespace Zayso\AreaBundle\Controller\Schedule;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultsController extends BaseController
{
    public function resultsAction(Request $request, $div, $pool)
    {           
        $data = array('div' => $div, 'pool' => $pool);
        
        $formType = $this->get('zayso_core.results.search.formtype');
        $form = $this->createForm($formType, $data);

        //if ($request->getMethod() == 'POST') // Form no longer posts, probably check to see if request has data
        {
            $form->bindRequest($request);

            if ($form->isValid()) // Fails if division was not selected
            {   
                $data = $form->getData();
                
                $request->getSession()->set('resultsSearchData',$data);
                
                return $this->redirect($this->generateUrl('zayso_core_schedule_results',$form->getData()));
            }
            else $form->setData($data);
        }
        if (!$div)
        {
            $data = $request->getSession()->get('resultsSearchData');
            if (is_array($data) && isset($data['div']) && $data['div'])
            {
                return $this->redirect($this->generateUrl('zayso_core_schedule_results',$data));
            }
        }
        
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
        $tplData['pools']      = $pools;
        $tplData['searchForm'] = $form->createView();
        
        return $this->renderx('Schedule:results.html.twig',$tplData);
        
    }
}
?>

<?php
namespace Zayso\S5GamesBundle\Controller\Admin\Person;

use Zayso\CoreBundle\Controller\BaseController;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends BaseController
{
    public function listAction(Request $request, $_format)
    {   
        $manager = $this->get('zayso_s5games.person.manager');
        $persons = $manager->loadFlatPersonsForProject($this->getProjectId());
        
        $tplData = array();
        $tplData['persons'] = $persons;
        
        return $this->render('ZaysoS5GamesBundle:Admin:Person/list.html.twig',$tplData);
        
        print_r($persons[1]);
        die('Count ' . count($persons));
        
        $outFileName = 'NatGamesPeople' . date('Ymd') . '.xls';
        
        $response = new Response();
        $response->setContent($export->generate());
        
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$outFileName\"");
        
        return $response;
        
        $accountManager = $this->getAccountManager();
        
        $params = array(
            'projectId' => $this->getProjectId(),
            'accountRelation' => 'Primary',
        );
        $accountPersons = $accountManager->getAccountPersons($params);
        
        $tplData = array();
        $tplData['accountPersons'] = $accountPersons;
        $tplData['accountPersonx'] = new AdminAccountListViewHelper($accountManager,$this->getProjectId());
        
        if ($_format == 'html') return $this->render('ZaysoAreaBundle:Admin:Account/list.html.twig',$tplData);
        
        $response = $this->render('ZaysoAreaBundle:Admin:Account/list.csv.php',$tplData);
        
      //$response->headers->set('Pragma', 'public');
      //$response->headers->set('Pragma', 'no-cache');
      //$response->headers->set('Expires','Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      //$response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
      //$response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');  // HTTP/1.1
      //$response->headers->set('Cache-Control', 'pre-check=0, post-check=0, max-age=0'); // HTTP/1.1

      //$response->headers->set('Cache-Control', 'public');
        $response->headers->set('Content-Type', 'text/csv');
      //$response->headers->set('Content-Transfer-Encoding', 'none');
        $response->headers->set('Content-Disposition', 'attachment; filename="accounts.csv"');
        
        return $response;
    }
}

<?php

namespace Zayso\CoreBundle\Controller\Admin\Person;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends BaseController
{
    protected function initSearchData($request)
    {
        $searchData = array
        (
            'lastName'  => null,
            'firstName' => null,
            'nickName'  => null,
            'aysoid'    => null,
            'region'    => null,
        );
        
        $searchDataSession = $request->getSession()->get('personSearch');
        
        if ($searchDataSession) $searchData = array_merge($searchData,json_decode($searchDataSession,true));
        
        return $searchData;
    }
    public function searchAction(Request $request, $_format)
    {
        // Build up the search data
        $searchData = $this->initSearchData($request);
        
        $searchFormType = $this->get('zayso_core.person.search.formtype');
        $searchForm = $this->createForm($searchFormType,$searchData);

        // Process Post
        if ($request->getMethod() == 'POST')
        {
            $searchForm->bindRequest($request);

            if ($searchForm->isValid())
            {
                $searchData = $searchForm->getData();
                $request->getSession()->set('personSearch',json_encode($searchData));
                return $this->redirect($this->generateUrl('zayso_core_admin_person_search'));
            }
        }
        
        // Do the search
        $manager = $this->get('zayso_core.person.manager');
        $persons = $manager->searchForPersons($searchData);
        
        // And render
        $tplData = array();
        $tplData['persons']    = $persons;
        $tplData['searchForm'] = $searchForm->createView();
        return $this->renderx('ZaysoCoreBundle:Admin/Person:search.html.twig',$tplData);
    }
}

<?php
/* --------------------------------------------------------------------
 * 06 Jun 2012
 * Make one just for s5games mostly because of difference in project plans
 * Also to refine nat games implementation
 * 
 * 04 May 2012
 * Person specific functionality
 * Might end up being extended by other managers
 * Keeping the right entity manager might be tricky
 */
namespace Zayso\S5GamesBundle\Manager;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Manager\BaseManager;
use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;
use Zayso\CoreBundle\Component\DataTransformer\RegionTransformer;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\ORMException;

class PersonManager extends BaseManager
{    
    public function loadPersonsForProject($projectId)
    {   
        $qb = $this->newQueryBuilder();

        $qb->addSelect('person,projectPerson','personCert','org');

        $qb->from('ZaysoCoreBundle:Person','person');
        
        $qb->leftJoin('person.org',              'org');
        
        $qb->leftJoin('person.projectPersons','projectPerson');
        
        $qb->leftJoin('person.registeredPersons','personCert'); 
        
//        $qb->leftJoin('person.registeredPersons','aysoCert', 
//            Expr\Join::WITH, $qb->expr()->eq('aysoCert.regType', 'AYSOV'));
        
        $qb->andWhere($qb->expr()->eq('projectPerson.project',$projectId));

        $qb->addOrderBy('person.lastName');
        $qb->addOrderBy('person.firstName');
        
        return $qb->getQuery()->getResult();        
    }
    public function loadFlatPersonsForProject($projectId)
    {
        $phoneTransformer  = new PhoneTransformer();
        
        $persons = array();
        
        $items = $this->loadPersonsForProject($projectId);
        foreach($items as $item)
        {
            $person = array();
          
            $person['id']        = $item->getId();
            $person['projectId'] = $projectId;
         
            $person['lastName']  = $item->getLastName();
            $person['firstName'] = $item->getFirstName();
            $person['nickName']  = $item->getNickName();
            $person['email']     = $item->getEmail();
            $person['cellPhone'] = $phoneTransformer->transform($item->getCellPhone());   
            
            $org = $item->getOrgz();
          
            $person['region']    = substr($org->getId(),4);
            $person['regionDesc']= $org->getDesc2();
            $person['state']     = $org->getState();
            
            $aysoCert = $item->getAysoCertz();
            $person['aysoid']    = substr($aysoCert->getRegKey(),5);
            $person['memYear']   = $aysoCert->getMemYear();
            $person['safeHaven'] = $aysoCert->getSafeHaven();
            $person['refBadge']  = $aysoCert->getRefBadge();
            
            $projectPerson = $item->getProjectPerson($projectId);
            $plans = $projectPerson->get('plans');
            if (!is_array($plans)) $plans = array();
            
            $planItems = array
            (
                'willAttend', 
                'willReferee',
                'willAssess',
                'wantAssess',
                'willCoach', 
                'willVolunteer',
                'havePlayer',
                'tshirtSize',
            );
            foreach($planItems as $planItem)
            {
                if (isset($plans[$planItem])) $person[$planItem] = $plans[$planItem];
                else                          $person[$planItem] = null;
            }
            $persons[$item->getId()] = $person;
        }
        return $persons;
        
    }
}
?>

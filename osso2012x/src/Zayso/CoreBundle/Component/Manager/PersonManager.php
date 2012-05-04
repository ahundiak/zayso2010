<?php
/* --------------------------------------------------------------------
 * 04 May 2012
 * Person specific functionality
 * Might end up being extended by other managers
 * Keeping the right entity manager might be tricky
 */
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

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
}
?>

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
    public function loadPersonForProject($projectId,$personId)
    {
        $qb = $this->newQueryBuilder();

        $qb->addSelect('person,projectPerson','org','teamRel','team');

        $qb->from('ZaysoCoreBundle:Person','person');
        $qb->andWhere($qb->expr()->eq('person.id',$qb->expr()->literal($personId)));
        
        $qb->leftJoin('person.org','org');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
        
        $qb->leftJoin('person.teamRels','teamRel',
             Expr\Join::WITH, $qb->expr()->eq('teamRel.project', $projectId));
        
        $qb->leftJoin('teamRel.team','team');
       
        //die($qb->getQuery()->getSQL());
        
        return $qb->getQuery()->getOneOrNullResult();      
    }
    
    public function loadPersonsForProject($projectId)
    {   
        $qb = $this->newQueryBuilder();

        $qb->addSelect('person,projectPerson','aysoCert','org','gameRel','game');

        $qb->from('ZaysoCoreBundle:Person','person');
        
        $qb->leftJoin('person.org',              'org');
        
        $qb->leftJoin('person.projectPersons','projectPerson');
        
//        $qb->leftJoin('person.registeredPersons','personCert'); 
        
        $qb->leftJoin('person.registeredPersons','aysoCert', 
            Expr\Join::WITH, $qb->expr()->eq('aysoCert.regType',  $qb->expr()->literal('AYSOV')));
        
        $qb->leftJoin('person.gameRels','gameRel');
        $qb->leftJoin('gameRel.event', 'game');
        
        $qb->andWhere($qb->expr()->eq('projectPerson.project',$projectId));

        $qb->addOrderBy('person.lastName');
        $qb->addOrderBy('person.firstName');
        
        return $qb->getQuery()->getResult();        
    }
    public function qbTeamsForProject($projectId)
    {
        $qb = $this->createQueryBuilder();
        $qb->addSelect('team');
        $qb->from('ZaysoCoreBundle:Team','team');
        
        $qb->andWhere($qb->expr()->in('team.project', $projectId));
      //$qb->andWhere($qb->expr()->in('team.type',    array('Physical','physical','Schedule')));
        
        $qb->addOrderBy('team.key1');

        return $qb;
    }
    public function getPersonTeamRelClass() { return 'Zayso\CoreBundle\Entity\PersonTeamRel'; }
    public function getTeamClass()          { return 'Zayso\CoreBundle\Entity\Team'; }
}
?>

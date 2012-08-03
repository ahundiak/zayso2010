<?php
namespace Zayso\CoreBundle\Manager;

use Zayso\CoreBundle\Entity\Account;

use Doctrine\ORM\Query\Expr;

class AccountManager extends BaseManager
{
    public function newAccount() { return new Account; }
    
    /* =========================================================================
     * Load account information
     * Includes all people and teams related to account
     */
    public function loadAccountWithEverything($projectId, $accountId)
    {
        // Build query
        $qb = $this->createQueryBuilder();

        $qb->addSelect('account');
        $qb->addSelect('accountPerson');
        $qb->addSelect('personPerson');
        $qb->addSelect('person');
        
        $qb->addSelect('personReg');
        $qb->addSelect('personRegOrg');
        $qb->addSelect('projectPerson');
        $qb->addSelect('openid');
        $qb->addSelect('teamRel');
        $qb->addSelect('team');

        $qb->from('ZaysoCoreBundle:Account','account');

        $qb->leftJoin('account.openids',   'openid');
        
        $qb->leftJoin('account.person',             'accountPerson');
        $qb->leftJoin('accountPerson.personPersons','personPerson');
        $qb->leftJoin('personPerson.person2',       'person');
            
        $qb->leftJoin('person.registeredPersons','personReg');
        $qb->leftJoin('personReg.org',           'personRegOrg');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
        
        $qb->leftJoin('person.teamRels','teamRel',
            Expr\Join::WITH, $qb->expr()->eq('teamRel.project', $projectId));
        
        $qb->leftJoin('teamRel.team','team');
        
        $qb->andWhere($qb->expr()->in('account.id',$accountId));
        
        return $qb->getQuery()->getOneOrNullResult(); 
    }
}

?>

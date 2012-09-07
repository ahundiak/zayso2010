<?php
namespace Zayso\CoreBundle\Manager;

use Zayso\CoreBundle\Entity\Account;
use Zayso\CoreBundle\Entity\ProjectPerson;
use Zayso\CoreBundle\Entity\PersonPerson;

use Doctrine\ORM\Query\Expr;

class AccountManager extends PersonManager
{
    public function newAccount($projectId = null) 
    {
        $account = new Account();
        if (!$projectId) return $account;
        
        // Probably should not go here
        $person = $account->getPerson();
        
        $project = $this->getProjectReference($projectId);
 
        $projectPerson = new ProjectPerson();
        $projectPerson->setPerson($person);
        $projectPerson->setProject($project);
       
        $person->addProjectPerson($projectPerson);
        
    }
    public function createAccount($account)
    {
        $person    = $account->getPerson();
        $personReg = $person->getRegAYSOV();
        
        // See if have a volunteer with the same id
        $personRegx = $this->loadPersonRegForKey($personReg->getRegKey());
        
        if (!$personRegx) $this->persist($personReg);
        else
        {
            // Just use the existing person
            $person = $personRegx->getPerson();   
        }
        if (!$person->getId()) 
        {
            $this->persist($person);
            
            $personPerson = new PersonPerson();
            $personPerson->setPerson1($person);
            $personPerson->setPerson2($person);
            $personPerson->setAsPrimary();
            $this->persist($personPerson);
        }
        $account->setPerson($person);
        
        if (!$account->getId()) $this->persist($account);
        
        $this->flush();
    }
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

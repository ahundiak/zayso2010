<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\NatGamesBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\CoreBundle\Entity\AccountOpenid;
use Zayso\CoreBundle\Entity\Account;
use Zayso\CoreBundle\Entity\AccountPerson;
use Zayso\CoreBundle\Entity\AccountPersonAyso;
use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\PersonRegistered;
use Zayso\CoreBundle\Entity\ProjectPerson;
use Zayso\CoreBundle\Entity\Project;
use Zayso\CoreBundle\Entity\Org;

class AccountManager
{
    protected $em = null;
    
    public function getEntityManager()
    {
        return $this->em;
    }

    public function __construct($em)
    {
        $this->em = $em;
    }
    /* =====================================================================
     * accountPerson is actuall a full structure of account information
     * try and create a new account with assorted checking and what not
     */
    public function createAccountFromAccountPersonAyso($accountPerson)
    {
        $em = $this->getEntityManager();

        // See if already have a person
        $personExisting = $this->loadPersonForAysoid($accountPerson->getAysoid());
        if ($personExisting)
        {
            // Make a new account for person already in system
            $personNew = $accountPerson->getPerson();
            $accountPerson->setPerson($personExisting);

            // Process any project info related to personNew
            foreach($personNew->getProjectPersons() as $projectPersonNew)
            {
                $projectIdNew = $projectPersonNew->getProject()->getId();

                // See if existing person is already attached to project
                $projectPersonExisting = $personExisting->getProjectPerson($projectIdNew);
                if (!$projectPersonExisting)
                {
                    // Load real project
                    $project = $em->getReference('ZaysoCoreBundle:Project',$projectIdNew);
                    $projectPersonNew->setProject($project);
                    $projectPersonNew->setPerson ($personExisting);
                }
            }
        }
        else
        {
            // Operate on new person
            $person = $accountPerson->getPerson();

            // For the new person need to add real project objects
            foreach($person->getProjectPersons() as $projectPerson)
            {
                // probably should through error if project not found
                $projectId = $projectPerson->getProject()->getId();
                $project = $em->getReference('ZaysoCoreBundle:Project',$projectId);
                $projectPerson->setProject($project);
            }

            // Need to see if have existing org
            $org = $this->loadOrg($person->getOrgKey(),true);
            if ($org) $person->setOrg($org);
        }
        // Verify any openid is valid, probably should not have to?

        // And save
        $em->persist($accountPerson->getAccountPerson()); // Everything cascades

        try
        {
            $em->flush();
        }
        catch (\Exception $e)
        {
            return 'Problem creating account';
            die('Create Account ' . $e->getMessage() . "\n");
        }
        return $accountPerson->getAccount();
    }
    /* ===========================================================
     * Sneak an organization call herw
     */
    public function loadOrg($orgId,$autoCreate = false)
    {
        $org = $this->getEntityManager()->find('ZaysoCoreBundle:Org',$orgId);
        if ($org) return $org;
        
        if (!$autoCreate) return null;
        if (!$orgId)      return null;
        
        $org = new Org();
        $org->setId($orgId);
        $this->getEntityManager()->persist($org);
        return $org;
    }
    // Idea is to build up a new account person model
    public function newAccountPersonAyso()
    {
        return new AccountPersonAyso();
    }
    // Idea is to build up a new account person model
    public function newAccountPerson($params = array())
    {
        // Basic ap
        $accountPerson = new AccountPerson();
        $accountPerson->setAccountRelation('Primary');
        $accountPerson->setVerified('No');
        $accountPerson->setStatus('Active');

        // New account
        $account       = new Account();
        $account->setStatus('Active');
        $accountPerson->setAccount($account);

        // New person
        $person = new Person();
        $person->setStatus('Active');
        $person->setVerified('No');
        $accountPerson->setPerson($person);

        // Assume one will be registered
        $registeredPerson = new PersonRegistered();
        $registeredPerson->setRegType ('AYSOV');
        $registeredPerson->setVerified('No');
        $registeredPerson->setPerson($person);

        // Assume assigned to a project
        $projectPerson = new ProjectPerson();
        $projectPerson->setStatus('Active');
        $projectPerson->setPerson($person);

        $todo = array('projectPlans' => true, 'openid' => true, 'projectLevels' => true);
        $projectPerson->set('todo',$todo);
        
        if (isset($params['projectId']))
        {
            $project = $this->getEntityManager()->getReference('ZaysoCoreBundle:Project',$params['projectId']);
            $projectPerson->setProject($project);
        }
        return $accountPerson;
    }
    public function getOrgForKey($id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('org');

        $qb->from('ZaysoCoreBundle:Org','org');

        $qb->andWhere($qb->expr()->eq('org.id',$qb->expr()->literal($id)));  
        
        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];
        return null;
    }
    public function newOrg() { return new Org(); }
    
    public function getAccountPersons($params = array())
    {
        if (isset($params['projectId'])) $wantProject = true;
        else                             $wantProject = false;

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('accountPerson');
        $qb->addSelect('account');
        $qb->addSelect('person');
        $qb->addSelect('registered');
        $qb->addSelect('org');

        if ($wantProject) $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson');

        $qb->leftJoin('accountPerson.account',   'account');
        $qb->leftJoin('accountPerson.person',    'person');
        $qb->leftJoin('person.registeredPersons','registered');
        $qb->leftJoin('person.orgKey',           'org');
        if ($wantProject)
        {
            $qb->leftJoin('person.projectPersons','projectPerson');
            $qb->leftJoin('projectPerson.project','project');
        }
        if (isset($params['accountId']))
        {
            $qb->andWhere($qb->expr()->in('account.id',$params['accountId']));
        }
        if (isset($params['accountPersonId']))
        {
            $qb->andWhere($qb->expr()->in('accountPerson.id',$params['accountPersonId']));
        }
        if (isset($params['accountRelation']))
        {
            $qb->andWhere($qb->expr()->eq('accountPerson.accountRelation',$qb->expr()->literal($params['accountRelation'])));
        }
        if ($wantProject)
        {
            $qb->andWhere($qb->expr()->in('project.id',$params['projectId']));
        }
        $query = $qb->getQuery();
        
      //die('DQL ' . $query->getSQL());
        return $query->getResult();        
    }
    public function getAccountPerson($params = array())
    {
        $accountPersons = $this->getAccountPersons($params);
        if (count($accountPersons) == 1) return $accountPersons[0];
        return null;
    }
    public function getAccounts($params = array())
    {
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('account');
        $qb->addSelect('accountPerson');
        $qb->addSelect('person');
        $qb->addSelect('registeredPerson');
        $qb->addSelect('projectPerson');
        $qb->addSelect('org');

        $qb->from('ZaysoCoreBundle:Account','account');

        $qb->leftJoin('account.accountPersons',   'accountPerson');
        $qb->leftJoin('accountPerson.person',     'person');
        $qb->leftJoin('person.registeredPersons', 'registeredPerson');
        $qb->leftJoin('person.projectPersons',    'projectPerson');
        $qb->leftJoin('projectPerson.project',    'project');
        $qb->leftJoin('person.org',               'org');

        if (isset($params['accountId']))
        {
            $qb->andWhere($qb->expr()->in('account.id',$params['accountId']));
        }
        if (isset($params['projectId']))
        {
            $qb->andWhere($qb->expr()->in('project.id',$params['projectId']));
        }
        $query = $qb->getQuery();

      //die('DQL ' . $query->getSQL());
        return $query->getResult();
    }
    /* ===========================================================
     * Allow multiple accounts per person
     * Still need to fool with the projectId
     * If person but no project then return just the person
     */
    public function loadPersonForAysoid($aysoid)
    {
        return $this->getPerson(array('aysoid' => $aysoid));
    }
    public function getPerson($params)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('registeredPerson');
        $qb->addSelect('projectPerson');
        $qb->addSelect('org');

        $qb->from('ZaysoCoreBundle:Person','person');

        $qb->leftJoin('person.registeredPersons','registeredPerson');
        $qb->leftJoin('person.projectPersons',   'projectPerson');
        $qb->leftJoin('projectPerson.project',   'project');
        $qb->leftJoin('person.org',              'org');

        if (isset($params['aysoid']))
        {
            $qb->andWhere($qb->expr()->eq('registeredPerson.regKey',':aysoid'));
        }
        if (isset($params['projectId']))
        {
            $qb->andWhere($qb->expr()->in('project.id',$params['projectId']));
        }
        $query = $qb->getQuery();
        $query->setParameter('aysoid',$params['aysoid']);
        
        $persons = $query->getResult();

        if (count($persons) == 1) return $persons[0];

        return null;
    }
    public function getProjectPerson($params)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:ProjectPerson','projectPerson');
        $qb->leftJoin('projectPerson.person', 'person');
        $qb->leftJoin('projectPerson.project','project');

        if (isset($params['personId']))
        {
            $qb->andWhere($qb->expr()->in('person.id',$params['personId']));
        }
        if (isset($params['projectId']))
        {
            $qb->andWhere($qb->expr()->in('project.id',$params['projectId']));
        }
        $query = $qb->getQuery();
        
        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }
    public function getOpenidsForAccount($accountId = 0)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('openid');
        $qb->addSelect('account');
        $qb->addSelect('accountPerson');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:AccountOpenid','openid');
        
        $qb->leftJoin('openid.accountPerson', 'accountPerson');
        $qb->leftJoin('accountPerson.account','account');
        $qb->leftJoin('accountPerson.person', 'person');

        $qb->andWhere($qb->expr()->eq('account.id',':accountId'));

        $query = $qb->getQuery();
        $query->setParameter('accountId',$accountId);

        return $query->getResult();
    }
    public function getOpenidForIdentifier($identifier)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('openid');
        $qb->addSelect('account');
        $qb->addSelect('accountPerson');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:AccountOpenid','openid');

        $qb->leftJoin('openid.accountPerson', 'accountPerson');
        $qb->leftJoin('accountPerson.account','account');
        $qb->leftJoin('accountPerson.person', 'person');

        $qb->andWhere($qb->expr()->eq('openid.identifier',':identifier'));

        $query = $qb->getQuery();
        $query->setParameter('identifier',$identifier);

        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }
    public function newOpenid($profile = array())
    {
        $openid = new AccountOpenid();
        $openid->setProfile($profile);

        return $openid;
    }
    public function deleteOpenid($id)
    {
        // $em->remove($entity)
        $dql = 'DELETE FROM ZaysoCoreBundle:AccountOpenid openid WHERE openid.id = :id';
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id',$id);
        $query->getResult();
    }
}
?>

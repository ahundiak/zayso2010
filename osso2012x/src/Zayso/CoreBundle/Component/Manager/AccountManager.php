<?php

namespace Zayso\CoreBundle\Component\Manager;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\AccountOpenid;
use Zayso\CoreBundle\Entity\Account;
use Zayso\CoreBundle\Entity\AccountPerson;
use Zayso\CoreBundle\Entity\AccountPersonAyso;
use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\PersonRegistered;
use Zayso\CoreBundle\Entity\ProjectPerson;
use Zayso\CoreBundle\Entity\Project;

class AccountManager extends BaseManager
{
    protected $masterPassword;
    
    public function __construct($em,$masterPassword)
    {
        parent::__construct($em);
        $this->masterPassword = $masterPassword;
    }
    public function getMasterPassword()  { return $this->masterPassword; }
    
    /* ===========================================================
     * For creating new account
     */
    public function newAccountPersonAyso()
    {
        return new AccountPersonAyso();
    }
    public function addAccountPersonAyso($accountPerson)
    {
        // Seems to work okay for adding a person as well
        $account = $this->createAccountFromAccountPersonAyso($accountPerson);
        if ($account) return $accountPerson->getAccountPerson();
        return null;
     }
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

            // Done in RegionValidator
            // Need to see if have existing org
            //$org = $this->loadOrg($person->getOrgKey(),true);
            //if ($org) $person->setOrg($org);
        }
        // Might want to fool around with existing account?

        // Normally require an openid but not always
        $accountPerson->validateOpenids();
        
        // And save
        $em->persist($accountPerson->getAccountPerson()); // Everything cascades

        try
        {
            $em->flush();
        }
        catch (\Exception $e)
        {
            // return 'Problem creating account';
            die('Create Account ' . $e->getMessage() . "\n");
        }
        return $accountPerson->getAccount();
    }
    
    /* ===========================================================
     * Person loading routines
     */
    public function loadPersonForAysoid($aysoid)
    {
        return $this->loadPerson(array('aysoid' => $aysoid));
    }
    public function loadPerson($params)
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
            $qb->andWhere($qb->expr()->eq('registeredPerson.regKey',$qb->expr()->literal($params['aysoid'])));
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
    /* =========================================================================
     * Some project person routines
     * Moved to AccountHomeManager
     */
    public function loadProjectPerson($projectId,$personId)
    {
        // Allow ids or objects
        $em = $this->getEntityManager();        
        if (is_object($projectId)) $projectId = $projectId->getId();
        if (is_object($personId))  $personId  = $personId->getId();
        
        // Build query
        $qb = $em->createQueryBuilder();

        $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:ProjectPerson','projectPerson');
        
        //$qb->leftJoin('projectPerson.person', 'person');
        //$qb->leftJoin('projectPerson.project','project');
    
        $qb->andWhere($qb->expr()->eq('projectPerson.project',':project'));
        $qb->andWhere($qb->expr()->eq('projectPerson.person', ':person' ));

        $params = array('project' => $projectId, 'person' => $personId);
        $qb->setParameters($params);
       
        $query = $qb->getQuery();

        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }
    /* ============================================================
     * Called when a person signs in to a paroject
     * Moved to AccountHomeManager
     */
    public function addProjectPerson($project,$person,$data = null)
    {
        // Allow ids or objects
        $em = $this->getEntityManager();        
        if (!is_object($project)) $project = $em->getReference('ZaysoCoreBundle:Project',$project);
        if (!is_object($person))  $person  = $em->getReference('ZaysoCoreBundle:Person', $person);
        
        // Check Dups
        $projectPerson = $this->loadProjectPerson($project,$person);
        if ($projectPerson) return $projectPerson;
        
        // Make a new one
        $projectPerson = new ProjectPerson();
        $projectPerson->setProject($project);
        $projectPerson->setPerson ($person);
        $projectPerson->setStatus('Active');
        
        // Handle data later
        try
        {
            $em->persist($projectPerson);
            $em->flush();
        }
        catch(\Exception $e)
        {
            die($e->getMessage());
            return 'Problem adding project person';
        }
        return $projectPerson;
    }
    public function loadPrimaryAccountPerson($accountId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('account');
        $qb->addSelect('accountPerson');
        $qb->addSelect('person');
        $qb->addSelect('registeredPerson');
        $qb->addSelect('projectPerson');
        $qb->addSelect('org');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson');

        $qb->leftJoin('accountPerson.account',   'account');
        $qb->leftJoin('accountPerson.person',    'person');
        $qb->leftJoin('person.registeredPersons','registeredPerson');
        $qb->leftJoin('person.projectPersons',   'projectPerson');
        $qb->leftJoin('projectPerson.project',   'project');
        $qb->leftJoin('person.org',              'org');
        
        $qb->andWhere($qb->expr()->eq('account.id',$qb->expr()->literal($accountId)));
        $qb->andWhere($qb->expr()->eq('accountPerson.accountRelation',$qb->expr()->literal('Primary')));
        
        return $qb->getQuery()->getOneOrNullResult();

        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
      
    }
    /* =========================================================================
     * Use this to present list of people on home page
     */
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
        $qb->addSelect('registeredPersons');
        $qb->addSelect('org');

        if ($wantProject) $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson');

        $qb->leftJoin('accountPerson.account',   'account');
        $qb->leftJoin('accountPerson.person',    'person');
        $qb->leftJoin('person.registeredPersons','registeredPersons');
        $qb->leftJoin('person.org',              'org');
        if ($wantProject)
        {
            $qb->leftJoin('person.projectPersons','projectPerson', 
                    Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $params['projectId']));
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
        if ($wantProject)
        {
            if ($params['projectId'])
            {
                // $qb->andWhere($qb->expr()->in('project.id',$params['projectId']));
            }
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
}
?>

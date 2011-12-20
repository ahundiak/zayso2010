<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\AreaBundle\Component\Manager;

use Zayso\ZaysoBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\ZaysoCoreBundle\Entity\AccountOpenid;
use Zayso\ZaysoCoreBundle\Entity\Account;
use Zayso\ZaysoCoreBundle\Entity\AccountPerson;
use Zayso\ZaysoCoreBundle\Entity\Person;
use Zayso\ZaysoCoreBundle\Entity\PersonRegistered;
use Zayso\ZaysoCoreBundle\Entity\ProjectPerson;
use Zayso\ZaysoCoreBundle\Entity\Project;

class AccountManager
{
    protected $em = null;
    
    public function getEntityManager() { return $this->em; }

    public function __construct($em,$masterPassword = null)
    {
        $this->em = $em;
        $this->masterPassword = $masterPassword;
    }
    public function getMasterPassword()  { return $this->masterPassword; }
    
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
            $project = $this->getEntityManager()->getReference('ZaysoBundle:Project',$params['projectId']);
            $projectPerson->setProject($project);
        }
        return $accountPerson;
    }
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
      //$qb->addSelect('org');

        if ($wantProject) $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson'); // memberx

        $qb->leftJoin('accountPerson.account','account');
        $qb->leftJoin('accountPerson.person', 'person');
        $qb->leftJoin('person.registereds',   'registered');
      //$qb->leftJoin('person.orgKey',        'org');
        if ($wantProject)
        {
            $qb->leftJoin('person.projects',      'projectPerson');
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
        $qb->addSelect('memberx');
        $qb->addSelect('person');
        $qb->addSelect('registered');
        $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:Account','account');

        $qb->leftJoin('account.members',      'memberx');
        $qb->leftJoin('memberx.person',       'person');
        $qb->leftJoin('person.registereds',   'registered');
        $qb->leftJoin('person.projects',      'projectPerson');
        $qb->leftJoin('projectPerson.project','project');

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
        return $this->loadPerson(array('aysoid' => $aysoid));
    }
    public function loadPerson($params)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('registered');
        $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:Person','person');

        $qb->leftJoin('person.registereds',   'registered');
        $qb->leftJoin('person.projects',      'projectPerson');
        $qb->leftJoin('projectPerson.project','project');

        if (isset($params['aysoid']))
        {
            $qb->andWhere($qb->expr()->eq('registered.regKey',$qb->expr()->literal($params['aysoid'])));
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
     */
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
    public function addProjectPerson($project,$person,$data = null)
    {
        // Allow ids or objects
        $em = $this->getEntityManager();
        
        if (!is_object($project)) $project = $em->getReference('ZaysoCoreBundle:Project',$project);
        if (!is_object($person))  $person  = $em->getReference('ZaysoCoreBundle:Person', $person);
        
        // Just to be safe, check dups
        $params = array
        (
            'personId'  => $person->getId(),
            'projectId' => $project->getId(),
        );
        $projectPerson = $this->getProjectPerson($params);
        if ($projectPerson) return $this->projectPerson;
        
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
    /* =====================================================================
     * Openid functionality
     * Maybe later move to it's own class just to reduce code size
     */
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
    public function loadOpenidForIdentifier($identifier)
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

        $qb->andWhere($qb->expr()->eq('openid.identifier',$qb->expr()->literal($identifier)));

        $query = $qb->getQuery();
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
        $dql = 'DELETE FROM ZaysoBundle:AccountOpenid openid WHERE openid.id = :id';
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id',$id);
        $query->getResult();
    }
    /* ================================================================
     * Takes care of linking an account to an openid profile
     * Lots of error checking, 
     * Return string if fails
     * Retuen new openid (flushed) if success
     */
    public function linkAccountToOpenid($account,$profile)
    {
        // Start with the openid entity
        $openid = new AccountOpenid();
        $openid->setProfile($profile);
        $identifier = $openid->getIdentifier();
        if (!$identifier) return 'No identifier in openid profile';
        
        // Verify don't already have one in 2012
        $openidExisting = $this->loadOpenidForIdentifier($identifier);
        if ($openidExisting) return 'Already have an openid for profile';
        
        // Only link to promary account person
        $accountPerson = $account->getPrimaryMember();
        if (!$accountPerson) return 'No primary account person';
        
        // Doit
        $openid->setAccountPerson($accountPerson);
        try
        {
            $this->getEntityManager()->persist($openid);
            $this->getEntityManager()->flush();
        }
        catch(\Exception $e)
        {
            die($e->getMessage());
            return 'Problem inserting openid record';
            return $e->getMessage();
        }
        return $openid;
    }
    /* ================================================================
     * Various ways to load basic account information
     */
    public function loadAccountForUserName($userName)
    {
        return $this->loadAccount(array('userName' => $userName));
    }
    public function loadAccountForAysoid($aysoid)
    {
        return $this->loadAccount(array('aysoid' => $aysoid));
    }
    public function loadAccount($params)
    {
        if (isset($params['userName'])) $userName = $params['userName'];
        else                            $userName = null;

        if (isset($params['aysoid']))   $aysoid = $params['aysoid'];
        else                            $aysoid = null;

        // Avoid loading everything
        if (!$userName && !$aysoid) return null;

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('account');
        $qb->addSelect('accountPerson');
        $qb->addSelect('person');
        $qb->addSelect('registered');
        
        $qb->from('ZaysoCoreBundle:Account','account');

        $qb->leftJoin('account.members',      'accountPerson');
        $qb->leftJoin('accountPerson.person', 'person');
        $qb->leftJoin('person.registereds',   'registered');

        if ($userName)
        {
            $qb->andWhere($qb->expr()->eq('account.userName',$qb->expr()->literal($userName)));
        }
        if ($aysoid)
        {
            $qb->andWhere($qb->expr()->eq('registered.regKey',           $qb->expr()->literal($aysoid)));
            $qb->andWhere($qb->expr()->eq('accountPerson.accountRelation',$qb->expr()->literal('Primary')));
        }
        $query = $qb->getQuery();
        $items = $query->getResult();
        if (count($items) == 1) return $items[0];
        return null;
    }
    /* =====================================================================
     * Hisome of the exact eneity class name details
     */
    public function newAccountEntity() { return new Account(); }
}
?>

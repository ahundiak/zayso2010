<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\NatGamesBundle\Component\Manager;

use Zayso\ZaysoBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\ZaysoBundle\Entity\AccountOpenid;
use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Entity\AccountPerson;
use Zayso\ZaysoBundle\Entity\Person;
use Zayso\ZaysoBundle\Entity\PersonRegistered;
use Zayso\ZaysoBundle\Entity\ProjectPerson;
use Zayso\ZaysoBundle\Entity\Project;

class AccountManager
{
    protected $em = null;
    protected $eaysoManager = null;
    
    public function getEntityManager()
    {
        return $this->em;
    }

    public function __construct($em)
    {
        $this->em = $em;
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

        if ($wantProject) $qb->addSelect('projectPerson');

        $qb->from('ZaysoBundle:AccountPerson','accountPerson'); // memberx

        $qb->leftJoin('accountPerson.account','account');
        $qb->leftJoin('accountPerson.person', 'person');
        $qb->leftJoin('person.registereds',   'registered');
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

        $qb->from('ZaysoBundle:Account','account');

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
    public function getPerson($params)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('registered');
        $qb->addSelect('projectPerson');

        $qb->from('ZaysoBundle:Person','person');

        $qb->leftJoin('person.registereds',   'registered');
        $qb->leftJoin('person.projects',      'projectPerson');
        $qb->leftJoin('projectPerson.project','project');

        if (isset($params['aysoid']))
        {
            $qb->andWhere($qb->expr()->eq('registered.regKey',':aysoid'));
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

        $qb->from('ZaysoBundle:ProjectPerson','projectPerson');
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

        $qb->from('ZaysoBundle:AccountOpenid','openid');
        
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

        $qb->from('ZaysoBundle:AccountOpenid','openid');

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
        $dql = 'DELETE FROM ZaysoBundle:AccountOpenid openid WHERE openid.id = :id';
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id',$id);
        $query->getResult();
    }
    public function loadVolCerts($aysoid)
    {
        if (substr($aysoid,0,5) != 'AYSOV') $aysoid = 'AYSOV' . $aysoid;

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('vol');
        $qb->addSelect('cert');

        $qb->from('EaysoBundle:Volunteer','vol');

        $qb->leftJoin('vol.certifications','cert');

        $qb->andWhere($qb->expr()->eq('vol.id',':aysoid'));
        $qb->setParameter('aysoid',$aysoid);

        $query = $qb->getQuery();
        try
        {
            $item = $query->getSingleResult();
        }
        catch (ORMException $e)
        {
            return null; // If none found
        }
        return $item;
    }
}
?>

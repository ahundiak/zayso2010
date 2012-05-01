<?php
/* ==================================================================
 * Try and isolate the various routines needed once a user signs in and
 * starts to use the system
 */
namespace Zayso\CoreBundle\Component\Manager;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\ProjectPerson;

class AccountHomeManager // extends BaseManager
{
    protected $em;
    
    public function __construct($em)
    {
        $this->em = $em;
    }
    public function getEntityManager() { return $this->em; }
    public function flush() { $this->em->flush(); }
    
    public function refresh($entity) { $this->em->refresh($entity); }
    
    /* =========================================================================
     * Loads a specific project person
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
    
        $qb->andWhere($qb->expr()->eq('projectPerson.project',$projectId));
        $qb->andWhere($qb->expr()->eq('projectPerson.person', $personId));

        $item = $qb->getQuery()->getOneOrNullResult();

        return $item;
    }
    /* ============================================================
     * Called when a person signs in to a project
     */
    public function addProjectPerson($project,$person, $data = null)
    {
        // Check Dups
        $projectPerson = $this->loadProjectPerson($project,$person);
        if ($projectPerson) return $projectPerson;
        
        // Allow ids or objects
        $em = $this->getEntityManager();        
        if (!is_object($project)) $project = $em->getReference('ZaysoCoreBundle:Project',$project);
        if (!is_object($person))  $person  = $em->getReference('ZaysoCoreBundle:Person', $person);
                
        // Make a new one
        $projectPerson = new ProjectPerson();
        $projectPerson->setProject($project);
        $projectPerson->setPerson ($person);
        $projectPerson->setStatus('Active');
        
        // Handle data later
        
        // Still not completely sure about this
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
    /* =========================================================================
     * Use this to present list of people on home page
     */
    public function loadAccountPersons($accountId, $projectId = 0)
    {
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('accountPerson');
        $qb->addSelect('account');
        $qb->addSelect('person');
        $qb->addSelect('registeredPersons');
        $qb->addSelect('org');
        $qb->addSelect('projectPerson');
        $qb->addSelect('openid');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson');

        $qb->leftJoin('accountPerson.account',   'account');
        $qb->leftJoin('accountPerson.person',    'person');
        $qb->leftJoin('accountPerson.openids',   'openid');
        $qb->leftJoin('person.registeredPersons','registeredPersons');
        $qb->leftJoin('person.org',              'org');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
        
        $qb->andWhere($qb->expr()->in('account.id',$accountId));
        
        $query = $qb->getQuery();
        
      //die('DQL ' . $query->getSQL());
        return $query->getResult();        
    }
    /* =========================================================================
     * Use to load and edit an account person
     */
    public function loadAccountPerson($accountPersonId, $projectId = 0)
    {
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('accountPerson');
        $qb->addSelect('account');
        $qb->addSelect('person');
        $qb->addSelect('registeredPersons');
        $qb->addSelect('org');
        $qb->addSelect('projectPerson');
        $qb->addSelect('openid');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson');

        $qb->leftJoin('accountPerson.account',   'account');
        $qb->leftJoin('accountPerson.person',    'person');
        $qb->leftJoin('accountPerson.openids',   'openid');
        $qb->leftJoin('person.registeredPersons','registeredPersons');
        $qb->leftJoin('person.org',              'org');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
        
        $qb->andWhere($qb->expr()->in('accountPerson.id',$accountPersonId));
        
        $item = $qb->getQuery()->getOneOrNullResult();

        return $item;   
    }
}
?>

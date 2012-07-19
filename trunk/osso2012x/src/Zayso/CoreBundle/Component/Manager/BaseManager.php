<?php
/* --------------------------------------------------------------------
 * Even though this is a base, move some of the functionality here for now
 * Later on want to refactor into a more specific manager
 * But have to many copies of stuff for now
 * 
 * 04 May 2012
 * Make this a real base manager class
 * Backed up all the other nonsense in AccountBaseManager
 */
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Project;
use Zayso\CoreBundle\Entity\ProjectField;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event;
use Zayso\CoreBundle\Entity\EventTeam;
use Zayso\CoreBundle\Entity\EventPerson;

class BaseManager
{
    protected $em = null;
    
    public function getEntityManager()     { return $this->em; }
    public function getEntityManagerName() { return $this->emName; }
    
    public function newQueryBuilder   ($entityClass = null) { return $this->em->createQueryBuilder($entityClass); }
    public function createQueryBuilder($entityClass = null) { return $this->em->createQueryBuilder($entityClass); }
    
    // Kind of hokay but think of this as an extended EntityManager
    public function clear()        { $this->em->clear(); }
    public function flush()        { $this->em->flush(); }
    public function remove ($item) { $this->em->remove ($item); }
    public function detach ($item) { $this->em->detach ($item); }
    public function persist($item) { $this->em->persist($item); }
    public function refresh($item) { $this->em->refresh($item); }
    
    public function __construct($em, $emName = null)
    {
        $this->em = $em;
        $this->emName = $emName;
    }    
    /* ========================================================
     * References are handy to have
     */
    public function getReference($name,$projectId)
    {
        return $this->getEntityManager()->getReference('ZaysoCoreBundle:' . $name,$projectId);
    }
    public function getProjectReference($projectId)
    {
        return $this->getReference('Project',$projectId);
    }
    public function getRegionReference($orgId)
    {
        return $this->getReference('Org',$orgId);
    }
    public function getTeamReference($teamId)
    {
        return $this->getReference('Team',$teamId);
    }
    public function getPersonReference($personId)
    {
        return $this->getReference('Person',$personId);
    }
    public function getAccountReference($accountId)
    {
        return $this->getReference('Account',$accountId);
    }
    public function getAccountPersonReference($accountPersonId)
    {
        return $this->getReference('AccountPerson',$accountPersonId);
    }
    /* =============================================================
     * Tuck some of these in here as well
     */
    public function getEventPersonClass() { return 'Zayso\CoreBundle\Entity\EventPerson'; }
    
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
    /* ================================================
     * For nested queries
     * projectId is for possible future use and consistency
     */
    public function qbPersonPersons($projectId, $personId1)
    {
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('distinct personPP.id');
        
        // Can't get this to work, really should not need the join
        // $qb->addSelect('distinct personPersonPP.person_id2');
        
        $qb->from('ZaysoCoreBundle:PersonPerson','personPersonPP');
        
        $qb->leftJoin('personPersonPP.person2','personPP');

        $qb->andWhere($qb->expr()->eq('personPersonPP.person1',$qb->expr()->literal($personId1)));
        
        return $qb;
    }
    public function loadPersonsForProjectPerson($projectId,$personId)
    {    
        $qbPersonPersons = $this->qbPersonPersons($projectId,$personId);
        
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('person');
        $qb->from('ZaysoCoreBundle:Person','person');
        
        // Project should also fit in here
        $qb->andWhere($qb->expr()->in('person.id', $qbPersonPersons->getDQL()));
        
        $qb->addOrderBy('person.nickName');
        $qb->addOrderBy('person.firstName');
        $qb->addOrderBy('person.lastName');
        
        return $qb->getQuery()->getResult();
    }
    public function loadTeamsForProjectPerson($projectId,$personId)
    {   
        $qbPersonPersons = $this->qbPersonPersons($projectId,$personId);
        
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('team');
        
        $qb->from('ZaysoCoreBundle:Team','team');
        
        $qb->leftJoin('team.personRels','personRel');
        
        $qb->andWhere($qb->expr()->eq('team.project',$qb->expr()->literal($projectId)));
        $qb->andWhere($qb->expr()->in('personRel.person',   $qbPersonPersons->getDQL()));
        
        $qb->addOrderBy('team.key1');
        
        return $qb->getQuery()->getResult();
    }
    /* ========================================================================
     * Returns a list of all the referees associated with a given account
     * and (eventually) project
     * Started from Area
     */
    public function loadOfficialsForPerson($projectId,$personId)
    {
        $qbPersonPersons = $this->qbPersonPersons($projectId,$personId);
       
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('personRegistered');
        
      //$qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:Person',       'person');
        $qb->leftJoin('person.personPersons',     'personPerson');
        $qb->leftJoin('person.registeredPersons', 'personRegistered'); // Limit to ayso
        
      //$qb->leftJoin('person.projectPersons',    'projectPerson');

        $qb->andWhere($qb->expr()->in('person.id',$qbPersonPersons->getDQL()));
        
        $qb->addOrderBy('person.nickName');
        $qb->addOrderBy('person.firstName');
        $qb->addOrderBy('person.lastName');

        $items = $qb->getQuery()->getResult();
        
        return $items;
    }
    public function loadPersonsForProject($projectId)
    {
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('person');
        
        $qb->from('ZaysoCoreBundle:Person','person');
        $qb->leftJoin('person.projectPersons','projectPerson');
        
        $qb->andWhere($qb->expr()->eq('projectPerson.project',$qb->expr()->literal($projectId)));
        
        $qb->addOrderBy('person.lastName,person.nickName,person.firstName');
        
        return $qb->getQuery()->getResult();
        
    }
}
?>

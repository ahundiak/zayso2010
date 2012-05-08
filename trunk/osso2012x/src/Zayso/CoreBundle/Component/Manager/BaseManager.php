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
    
    public function getEntityManager() { return $this->em; }
    public function newQueryBuilder($entityClass = null) { return $this->em->createQueryBuilder($entityClass); }
    
    // Kind of hokay but think of this as an extended EntityManager
    public function clear()        { $this->em->clear(); }
    public function flush()        { $this->em->flush(); }
    public function remove ($item) { $this->em->remove ($item); }
    public function detach ($item) { $this->em->detach ($item); }
    public function persist($item) { $this->em->persist($item); }
    public function refresh($item) { $this->em->refresh($item); }
    
    public function __construct($em)
    {
        $this->em = $em;
    }    
    /* ========================================================
     * References are handy to have
     */
    public function getProjectReference($projectId)
    {
        return $this->getEntityManager()->getReference('ZaysoCoreBundle:Project',$projectId);
    }
    public function getRegionReference($orgId)
    {
        return $this->getEntityManager()->getReference('ZaysoCoreBundle:Org',$orgId);
    }
    public function getPersonReference($personId)
    {
        return $this->getEntityManager()->getReference('ZaysoCoreBundle:Person',$personId);
    }
    public function getAccountReference($accountId)
    {
        return $this->getEntityManager()->getReference('ZaysoCoreBundle:Account',$accountId);
    }
}
?>
<?php
/* --------------------------------------------------------------------
 * 30 July 2012
 * 
 * Wrapper for entity manager
 */
namespace Zayso\CoreBundle\Manager;

class BaseManager
{
    protected $em = null;
    protected $emName = null;
    
    public function getEntityManager()     { return $this->em; }
    public function getEntityManagerName() { return $this->emName; }
    
    public function createQueryBuilder($entityClass = null) { return $this->em->createQueryBuilder($entityClass); }
    
    // Kind of hokay but think of this as an extended EntityManager
    public function clear()        { $this->em->clear(); }
    public function flush()        { $this->em->flush(); }
    public function remove ($item) { $this->em->remove ($item); }
    public function detach ($item) { $this->em->detach ($item); }
    public function persist($item) { $this->em->persist($item); }
    public function refresh($item) { $this->em->refresh($item); }
    
    public function __construct($em, $emName = 'default')
    {
        $this->em     = $em;
        $this->emName = $emName;
    }
}
?>

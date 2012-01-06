<?php
namespace Zayso\AreaBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Project;
use Zayso\CoreBundle\Entity\ProjectField;

class BaseManager
{
    protected $em = null;
    
    public function getEntityManager() { return $this->em; }
    
    public function clear()        { $this->em->clear(); }
    public function flush()        { $this->em->flush(); }
    public function remove ($item) { $this->em->remove($item);  }
    public function persist($item) { $this->em->persist($item); }
    
    public function __construct($em)
    {
        $this->em = $em;
    }    
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
}
?>
